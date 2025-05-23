<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderBillingDetails extends Model
{
    use HasFactory;

    protected $table = 'order_billing_details';
    public $incrementing = false; 
    protected $keyType = 'string'; 

    protected $fillable = [
        'id',
        'user_id',
        'country',
        'first_name',
        'last_name',
        'landmark_company_building',
        'address',
        'apartment_suite_unit',
        'state_country',
        'postal_zip',
        'email_address',
        'phone',
        'order_notes',
        'coupon_code',
        'product_id',
        'product_details',
        'cart_subtotal',
        'discount_total',
        'order_total',
        'is_paid',
        'user_id',
        'mode_of_payment',
        'payment_reference'
    ];
}
