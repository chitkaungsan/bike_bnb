<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    public function bike(): BelongsTo{
        return $this->bleongsTo(Bike::class);
    }
}
