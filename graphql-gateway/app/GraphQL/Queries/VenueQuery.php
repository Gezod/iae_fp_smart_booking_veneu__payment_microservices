<?php

namespace App\GraphQL\Queries;

use Illuminate\Support\Facades\Http;

class VenueQuery
{
    public function __invoke($_, array $args)
    {
        $response = Http::get('http://venue-service/api/venues');

        if ($response->successful()) {
            return $response->json();
        }

        return [];
    }
}
