<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;

    protected $table = 'coupons';
    protected $primaryKey = 'id';

    protected $fillable = [
        'coupon_code',
        'discount',
        'discount_type',
        'discount_value',
        'min_order_amount',
        'max_discount_amount',
        'start_date',
        'expiry_date',
        'usage_limit',
        'used_count',
        'is_active',
        'created_at'
    ];
}
