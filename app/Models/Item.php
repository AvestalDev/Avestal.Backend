<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model {

    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'specification',
        'price',
        'images',
        'preview_image',
        'status',
        'type',
        'unit_type',
        'quantity',
        'discount',
        'category_id',
        'subcategory_id'
    ];

    public function category() {
        return $this->hasOne('App\Models\Category', 'id', 'category_id');
    }

    public function subcategory() {
        return $this->hasOne('App\Models\Subcategory', 'id', 'subcategory_id');
    }

    protected $casts = [
        'specification' => 'array',
        'images' => 'array'
    ];
}
