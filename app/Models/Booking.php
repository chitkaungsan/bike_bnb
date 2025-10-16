<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';

    protected $fillable = [
        'bike_id',
        'rider_id',
        'start_date',
        'end_date',
        'status',
        'name',
        'phone',
        'days',
        'daily_rate',
        'total_price',
        'payment_type',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
}
