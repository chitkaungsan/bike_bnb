<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreImage extends Model
{
    use HasFactory;

    protected $table = 'store_images';

    // Mass assignable fields
    protected $fillable = [
        'store_id',
        'image_path',
        'is_main',
        
    ];

    // Optional: disable timestamps if not needed
    // public $timestamps = false;
}
