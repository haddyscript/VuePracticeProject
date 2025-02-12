<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageDetails extends Model
{
    use HasFactory;

    protected $table = 'page_details';
    protected $primaryKey = 'id';

    protected $fillable = [
        'page',
        'title',
        'description',
    ];

}
