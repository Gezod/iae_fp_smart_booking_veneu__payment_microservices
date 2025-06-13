<?php

namespace App\Listeners;

use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class ProcessPaymentOnBookingCreated
{
    /**
     * Handle the event.
     *
     * @param  mixed  $event  // event ini harus mengandung data booking
     * @return void
     */
    public function handle($event)
    {
        $bookingData = $event->booking; // asumsi event mengirim properti booking

        // Cek apakah payment sudah ada untuk booking ini
        $existing = Payment::where('booking_id', $bookingData['id'])->first();
        if ($existing) {
            Log::info("Payment sudah ada untuk booking_id: {$bookingData['id']}");
            return;
        }

        // Buat payment baru
        Payment::create([
            'booking_id' => $bookingData['id'],
            'amount'     => $bookingData['total_price'],
            'status'     => 'paid',
        ]);

        Log::info("Payment dibuat untuk booking_id: {$bookingData['id']}");
    }
}
