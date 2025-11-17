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
        'email',
        'phone',
        'days',
        'daily_rate',
        'total_price',
        'payment_type',
        'payment_status',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function holdPayment()
    {
        $this->payment_status = 'held';
        $this->save();
    }

    public function releasePayment()
    {
        $this->payment_status = 'released';
        $this->save();
    }

    public function user()
{
    return $this->belongsTo(User::class, 'rider_id');
}

    public function bike()
    {
        return $this->belongsTo(Bike::class);
    }

}
