<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'description',
        'price',
        'discount_price',
        'stock_quantity',
        'weight',
        'dimensions',
        'manufacturer',
        'brand',
        'color',
        'size',
        'material',
        'status',
        'featured',
        'image_url',
        'thumbnail_url',
        'video_url',
        'tags',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'stock_status',
    ];

    public function productImages() {
        return $this->hasMany(ProductImage::class);
    }
    public function cart() {
        return $this->hasMany(Cart::class);
    }
}
