<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessDetail extends Model
{
    protected $table = 'business_detail';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'founded_year',
        'owner_name',
        'phone_number',
        'email',
        'website_url',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'social_media_links',
        'business_hours',
        'logo_url',
        'edited_by',
        'contact_name',
        'live'
    ];

    protected $casts = [
        'social_media_links' => 'array',
    ];
}
