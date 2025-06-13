<?php

namespace App\GraphQL\Queries;

use Illuminate\Support\Facades\Http;

class PaymentQuery
{
    public function __invoke($_, array $args)
    {
        $response = Http::get('http://payment-service/api/payments');

        if ($response->successful()) {
            return $response->json();
        }

        return [];
    }
}
