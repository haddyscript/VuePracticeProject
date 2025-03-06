<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use Illuminate\Support\Facades\Log;

class CountryController extends Controller
{
    public function getCountry()
    {
        $countries = Country::select('id', 'code', 'name')->get();
        return response()->json(
            [
                'data' => $countries
            ]
        ); 
    }
}
