<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Bike extends Model
{
        use HasFactory;
      protected $fillable = [
        'store_id',
        'user_id',
        'title',
        'description',
        'price',
        'photo',
        'license_image',
        'model',
        'year',
        'brand_id',
        'type',
        'cat_id',
        'bike_no',
        'status',
        'user_id'
    ];
}
