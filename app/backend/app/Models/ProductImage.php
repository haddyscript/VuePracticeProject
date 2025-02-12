<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductImage extends Model
{
    use HasFactory;

    protected $table = 'product_images';
    protected $primaryKey = 'id';

    protected $fillable = [
        'product_id',
        'name',
        'image_data'
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
