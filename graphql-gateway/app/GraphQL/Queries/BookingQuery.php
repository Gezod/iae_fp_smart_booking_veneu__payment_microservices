<?php

namespace App\GraphQL\Queries;

use Illuminate\Support\Facades\Http;

class BookingQuery
{
    public function __invoke($_, array $args)
    {
        $response = Http::get('http://booking-service/api/bookings');

        if ($response->successful()) {
            return $response->json();
        }

        return [];
    }
}
