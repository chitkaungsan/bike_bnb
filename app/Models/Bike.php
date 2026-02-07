<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bike extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id', 'user_id', 'title', 'description', 'price',
        'photo', 'license_image', 'model', 'year', 'brand_id',
        'type', 'cat_id', 'bike_no', 'status'
    ];

    /**
     * Get the images for the bike.
     */
    public function images(): HasMany
    {
        // Laravel assumes the foreign key is bike_id
        return $this->hasMany(BikeImage::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }


    public function brand(): BelongsTo {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo {
        return $this->belongsTo(Category::class, 'cat_id'); // specifying cat_id since it's custom
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

}
