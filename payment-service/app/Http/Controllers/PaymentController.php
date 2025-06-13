<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function index()
    {
        return response()->json(Payment::all());
    }

    public function show($id)
    {
        $payment = Payment::find($id);
        return $payment ? response()->json($payment) : response()->json(['error' => 'Not found'], 404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|integer'
        ]);

        // Ambil detail booking dari Booking Service
        $bookingResponse = Http::get('http://booking-service/api/bookings/' . $request->booking_id);

        if (!$bookingResponse->ok()) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        $booking = $bookingResponse->json();

        // Cek apakah sudah pernah dibayar
        $existing = Payment::where('booking_id', $booking['id'])->first();
        if ($existing) {
            return response()->json(['message' => 'Booking already paid'], 200);
        }

        // Simpan pembayaran
        $payment = Payment::create([
            'booking_id' => $booking['id'],
            'amount'     => $booking['total_price'],
            'status'     => 'paid'
        ]);

        Log::info('Payment created manually via API', ['booking_id' => $booking['id']]);

        return response()->json($payment, 201);
    }
}
