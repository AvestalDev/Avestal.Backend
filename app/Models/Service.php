<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model {

    use HasFactory;

    protected $fillable = [
        'title', 'text', 'price', 'address', 'geolocation', 'images', 'files', 'items', 'rating'
    ];

    protected $casts = [
        'geolocation' => 'array',
        'images' => 'array',
        'files' => 'array',
        'items' => 'array'
    ];

    public function responses() {
        return $this->hasMany('App\Models\Response', 'service_id', 'id');
    }
}
