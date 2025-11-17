<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    // Allow mass assignment for these fields
    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
    ];

    /**
     * Relationship: A city has many stores.
     */
    public function stores()
    {
        return $this->hasMany(Store::class);
    }
}
