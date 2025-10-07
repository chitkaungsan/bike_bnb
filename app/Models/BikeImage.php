<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BikeImage extends Model
{
     use HasFactory;
    protected $fillable = [
        'bike_id',
        'store_id',
        'user_id',
        'title',
        'photo',
    ];
}
