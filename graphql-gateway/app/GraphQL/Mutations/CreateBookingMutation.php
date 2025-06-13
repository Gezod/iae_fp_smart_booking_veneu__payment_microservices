<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CreateBookingMutation
{
    public function __invoke($_, array $args)
    {
        $input = $args['input'];

        $payload = [
            'user_id'     => $input['user_id'],
            'venue_id'    => $input['venue_id'],
            'slot_booked' => $input['slot_booked'],
            'start_time'  => $input['start_time'],
            'end_time'    => $input['end_time'],
        ];

        $response = Http::post('http://booking-service/api/bookings', $payload);

        if ($response->successful() || $response->status() === 201) {
            $data = $response->json('data'); // Ambil bagian 'data'

            return [
                'id'             => $data['id'],
                'user_id'        => $data['user_id'],
                'venue_id'       => $data['venue_id'],
                'venue_name'     => $data['venue_name'],
                'slot_booked'    => $data['slot_booked'],
                'price_per_slot' => $data['price_per_slot'],
                'total_price'    => $data['total_price'],
                'start_time'     => $data['start_time'],
                'end_time'       => $data['end_time'],
            ];
        }

        // Kalau error, log isi responsenya
        Log::error('CreateBookingMutation failed', [
            'payload' => $payload,
            'status'  => $response->status(),
            'body'    => $response->body(),
        ]);

        throw new \Exception('BookingService Error: ' . $response->body());
    }
}
