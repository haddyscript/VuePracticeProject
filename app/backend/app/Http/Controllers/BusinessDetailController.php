<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\BusinessDetail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BusinessDetailController extends Controller
{
    public function createBusinessGeneralDetail(Request $request){

        // Log::info('Incoming Request for Business Details: ' . var_export($request->all(), true));
        $admin = $request->user();
        $business = BusinessDetail::first();

        if ($business) {
            // Update existing business
            $business->update([
                'name' => $request->name,
                'description' => $request->description,
                'owner_name' => $request->owner_name,
                'contact_name' => $request->contact_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number, 
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
                'postal_code' => $request->postal_code,
                'business_hours' => $request->business_hours,
                'founded_year' => $request->founded_year,
                'edited_by' => $admin->full_name
            ]);
    
            return response()->json(['success' => "true", 'message' => 'Business details updated successfully!', 'business' => $business]);
        } else {
            // Create new business
            $business = BusinessDetail::create([
                'name' => $request->name,
                'description' => $request->description,
                'owner_name' => $request->owner_name,
                'contact_name' => $request->contact_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number, 
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
                'postal_code' => $request->postal_code,
                'business_hours' => $request->business_hours,
                'founded_year' => $request->founded_year,
                'edited_by' => $admin->full_name
            ]);
            
            if($business->save()){
                return response()->json(['success' => "true", 'message' => 'Business details created successfully!' , 'business' => $business]);
            }else{
                return response()->json(['success' => "false", 'message' => 'Failed to create business details!']);
            }
        }
    }

    public function getBusinessGeneralDetail(){
        $business = BusinessDetail::first();

        if($business){
            return response()->json(['success' => "true", 'message' => 'Business details fetched successfully!', 'business' => $business], 200);
        }else{
            return response()->json(['success' => "false", 'message' => 'There is no business details yet!'], 200);
        }
    }
}
