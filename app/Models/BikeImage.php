<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    /**
     * Get the bike that owns the image.
     */
    public function bike(): BelongsTo
    {
        return $this->belongsTo(Bike::class);
    }

    public function store(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
