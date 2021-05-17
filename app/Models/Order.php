<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {

    use HasFactory;

    protected $fillable = [
        'title', 'text', 'price', 'address', 'geolocation', 'images', 'files', 'items'
    ];

    protected $casts = [
        'geolocation' => 'array',
        'images' => 'array',
        'files' => 'array',
        'items' => 'array'
    ];
}
