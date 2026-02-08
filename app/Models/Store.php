<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'location',
        'logo',
        'cover_photo',
        'phone',
        'address',
    ];

    /**
     * Store belongs to a User (owner/host)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bikes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Bike::class);
    }

    public function bikeImages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BikeImage::class);
    }
    public function City(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
