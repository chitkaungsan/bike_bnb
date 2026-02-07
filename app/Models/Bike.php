<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function images(): HasMany{
        return $this->hasMany(BikeImages::class);
    }
}
