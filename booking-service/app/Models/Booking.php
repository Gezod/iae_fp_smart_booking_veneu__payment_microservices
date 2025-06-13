<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'venue_id',
        'venue_name',
        'slot_booked',
        'price_per_slot',
        'total_price',
        'start_time',
        'end_time',
    ];
}
