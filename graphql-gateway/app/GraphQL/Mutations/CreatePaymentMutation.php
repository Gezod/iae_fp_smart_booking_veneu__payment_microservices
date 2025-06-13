<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Http;

class CreatePaymentMutation
{
    public function __invoke($_, array $args)
    {
        $response = Http::post('http://payment-service/api/payments', [
            'booking_id' => $args['booking_id'],
            'amount' => $args['amount'],
            'status' => $args['status'],
            'paid_at' => $args['paid_at'],
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}
