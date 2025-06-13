<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Events\BookingCreatedEvent;

class BookingController extends Controller
{
    public function index()
    {
        return response()->json(Booking::all());
    }

    public function show($id)
    {
        $booking = Booking::find($id);
        return $booking ? response()->json($booking) : response()->json(['error' => 'Not found'], 404);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'     => 'required|integer',
            'venue_id'    => 'required|integer',
            'slot_booked' => 'required|integer|min:1',
            'start_time'  => 'required|date',
            'end_time'    => 'required|date|after:start_time',
        ]);

        $venueResponse = Http::get('http://venue-service/api/venues/' . $data['venue_id']);
        if (!$venueResponse->ok()) {
            return response()->json(['error' => 'Venue tidak ditemukan'], 404);
        }

        $venue = $venueResponse->json();
        if ($venue['available_slots'] < $data['slot_booked']) {
            return response()->json(['error' => 'Slot tidak mencukupi'], 400);
        }

        $price = $venue['price_per_hour'];
        $total = $price * $data['slot_booked'];

        $booking = Booking::create([
            'user_id'        => $data['user_id'],
            'venue_id'       => $data['venue_id'],
            'venue_name'     => $venue['name'],
            'slot_booked'    => $data['slot_booked'],
            'price_per_slot' => $price,
            'total_price'    => $total,
            'start_time'     => $data['start_time'],
            'end_time'       => $data['end_time'],
        ]);

        Http::patch('http://venue-service/api/venues/' . $data['venue_id'] . '/decrement-slot', [
            'slot' => $data['slot_booked'],
        ]);

        // ⬇️ Kirim event ke RabbitMQ dan log jika berhasil
        event(new BookingCreatedEvent($booking->toArray()));
        Log::info('BookingCreatedEvent dispatched to RabbitMQ', ['booking_id' => $booking->id]);

        return response()->json([
            'message' => 'Booking berhasil dan event dikirim ke RabbitMQ',
            'data' => $booking
        ], 201);
    }

    public function storeWithoutRabbit(Request $request)
{
    $data = $request->validate([
        'user_id'     => 'required|integer',
        'venue_id'    => 'required|integer',
        'slot_booked' => 'required|integer|min:1',
        'start_time'  => 'required|date',
        'end_time'    => 'required|date|after:start_time',
    ]);

    $venueResponse = Http::get('http://venue-service/api/venues/' . $data['venue_id']);
    if (!$venueResponse->ok()) {
        return response()->json(['error' => 'Venue tidak ditemukan'], 404);
    }

    $venue = $venueResponse->json();
    if ($venue['available_slots'] < $data['slot_booked']) {
        return response()->json(['error' => 'Slot tidak mencukupi'], 400);
    }

    $price = $venue['price_per_hour'];
    $total = $price * $data['slot_booked'];

    $booking = Booking::create([
        'user_id'        => $data['user_id'],
        'venue_id'       => $data['venue_id'],
        'venue_name'     => $venue['name'],
        'slot_booked'    => $data['slot_booked'],
        'price_per_slot' => $price,
        'total_price'    => $total,
        'start_time'     => $data['start_time'],
        'end_time'       => $data['end_time'],
    ]);

    // Tetap kirim permintaan untuk mengurangi slot
    Http::patch('http://venue-service/api/venues/' . $data['venue_id'] . '/decrement-slot', [
        'slot' => $data['slot_booked'],
    ]);

    return response()->json([
        'message' => 'Booking berhasil (tanpa RabbitMQ)',
        'data' => $booking
    ], 201);
}

    public function update(Request $request, $id)
{
    $data = $request->validate([
        'slot_booked' => 'required|integer|min:1',
        'start_time'  => 'required|date',
        'end_time'    => 'required|date|after:start_time',
    ]);

    $booking = Booking::find($id);
    if (!$booking) {
        return response()->json(['error' => 'Booking tidak ditemukan'], 404);
    }

    // Ambil data venue terkini untuk hitung ulang harga
    $venueResponse = Http::get('http://venue-service/api/venues/' . $booking->venue_id);
    if (!$venueResponse->ok()) {
        return response()->json(['error' => 'Venue tidak ditemukan'], 404);
    }

    $venue = $venueResponse->json();
    if ($venue['available_slots'] + $booking->slot_booked < $data['slot_booked']) {
        return response()->json(['error' => 'Slot tidak mencukupi untuk update'], 400);
    }

    // Update harga dan total
    $price = $venue['price_per_hour'];
    $total = $price * $data['slot_booked'];

    // Update booking
    $booking->update([
        'slot_booked'    => $data['slot_booked'],
        'price_per_slot' => $price,
        'total_price'    => $total,
        'start_time'     => $data['start_time'],
        'end_time'       => $data['end_time'],
    ]);

    return response()->json(['message' => 'Booking berhasil diupdate', 'data' => $booking]);
}


    public function destroy($id)
    {
        $booking = Booking::find($id);
        if (!$booking) return response()->json(['error' => 'Not found'], 404);

        $booking->delete();
        return response()->json(['message' => 'Booking deleted']);
    }
}
