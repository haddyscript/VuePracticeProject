<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentEwallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_id',
        'email',
        'amount',
        'ewallet_type',
        'status',
        'redirect_url',
        'reference_id'
    ];
}
