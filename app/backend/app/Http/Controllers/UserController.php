<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Checkout;
use Carbon\Carbon;

use function PHPUnit\Framework\isEmpty;

class UserController extends Controller
{
    public function registerUser(Request $request){

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $findUserEmail = User::where('email', $request->email)->first();

        if($findUserEmail){
            return response()->json(array(
                'success' => "false",
                'status' =>'error',
                'message' => 'Email already exists'
            ), 200);
        }

        if($request->password != $request->confirm_password){
            return response()->json(array(
                'success' => "false",
                'status' => 'error',
                'message' => 'Password and password confirmation do not match'
            ), 200);
        }

        if($validator->fails()){
            return response()->json(array(
                'success' => "false",
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ), 200);
        }

        $role = 'user';
        $user = User::create(array(
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'gender' => $request->gender,
            'role' => $role,
            'password' => Hash::make($request->password)
        ));

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => "true",
            'message' => 'User registered successfully!',
            'user' => $user,
            'token' => $token
        ], 201);
    }
    

    public function login(Request $request){

        $user = User::where('email', $request->email)->first();

        $validate = $this->validateData($user, $request);

        if($validate != null){
            return $validate;
        }

        return response()->json(array(
            'success' => "false",
            'status' => 'error',
            'message' => 'Invalid credentials'
        ));
    }

    public function validateData($user, $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails()){
             $result = response()->json(array(
                'success' => "false",
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ), 200);
        }
        
        if(!$user){
            $result = response()->json(array(
                'success' => "false",
                'status' => 'error',
                'message' => 'User not found, check your email and try again!'
            ), 200);
        }

        if($user && !Hash::check($request->password, $user->password)){
            $result = response()->json(array(
                'success' => "false",
                'status' => 'error',
                'message' => 'Incorrect password, please try again!'
            ), 200);

        }

        if($user && Hash::check($request->password, $user->password)){

            $user->makeHidden('profile_picture');
            $token = $user->createToken('auth_token')->plainTextToken;
            $checkExistingCheckout = $this->checkExistingCheckout($user->id);
            $result = response()->json(
                array(
                    'success' => "true",
                    'status' => 'success',
                    'message' => 'User logged in successfully',
                    'user' => $user,
                    'token' => $token,
                    'have_checkout' => $checkExistingCheckout
                ), 200
            );
        }
        return $result;
    }
    private function checkExistingCheckout($user_id){
        $checkout = Checkout::where('user_id', $user_id)->where('is_place_order', 0)->first();
        return $checkout ? true : false;
    }

    public function logout(Request $request){

        $user = $request->user();

        if ($user && $user->tokens->isNotEmpty()) {
            $user->tokens->each(function ($token) {
                $token->delete();
            });

            return response()->json(array(
                'status' => 'success',
                'message' => 'Logged out successfully'
            ));
        }else {
            return response()->json(['status' => 'error', 'message' => 'No active tokens to revoke'], 200);
        }
        
        return response()->json(array(
            'status' => 'error',
            'message' => 'Unauthorized'
        ), 401);
    }

    public function updateUser(Request $request)
    {
        $checkAge = $this->checkAge($request);
        if($checkAge != null){
            return $checkAge;
        }
        $user = User::where('id', $request->user_id)->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'postal_code' => $request->postal_code
        ]);
        if (!$user) {
            return response()->json([
                'success' => "false",
                'status' => 'error',
                'message' => 'User details update failed, please try again!',
            ]);
        }

        return response()->json([
            'success' => 'true',
            'status' => 'success',
            'message' => 'Profile updated successfully'
        ], 200);
    }

    private function checkAge($request){
        if (!empty($request->date_of_birth)) {  
            $birthdate = strtotime($request->date_of_birth); 
            $birthYear = date('Y', $birthdate);
            $birthMonth = date('m', $birthdate);
            $birthDay = date('d', $birthdate);
        
            // Get current date
            $currentYear = date('Y');
            $currentMonth = date('m');
            $currentDay = date('d');
        
            $age = $currentYear - $birthYear;
        
            if (($currentMonth < $birthMonth) || ($currentMonth == $birthMonth && $currentDay < $birthDay)) {
                $age--;
            }
        
            if ($age < 18) {
                return response()->json([
                    'success' => 'false',
                    'status' => 'error',
                    'message' => 'User must be at least 18 years old'
                ], 200);
            }
        }
        return null;
    }

    public function uploadProfilePicture(Request $request)
    {
        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json(['success' => 'false', 'message' => 'User not found'], 404);
        }

        if (!$request->hasFile('profile_picture')) {
            return response()->json(['success' => 'false', 'message' => 'No file uploaded'], 400);
        }

        $file = $request->file('profile_picture');
        $profilePicture = file_get_contents($file->getRealPath()); 

        $user->update(['profile_picture' => $profilePicture]);

        return response()->json([
            'success' => 'true',
            'message' => 'Profile picture uploaded successfully',
        ], 200);
    }

    public function adminGetUsersList(Request $request) {
        $search = $request->get('search', []);
        $page = $request->get('status')['page'] ?? 1;
        $perPage = 15;
    
        $query = User::query();
    
        if(!empty($search['user_id'])){
            $query->where("id", $search['user_id']);
        }

        // Filter by creation date
        if (!empty($search['recent'])) {
            $query->orderBy('created_at', 'desc');
        }
        if (!empty($search['this_week'])) {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        }
        if (!empty($search['this_month'])) {
            $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
        }
        if (!empty($search['last_3_months'])) {
            $query->whereBetween('created_at', [now()->subMonths(3)->startOfMonth(), now()->endOfMonth()]);
        }
    
        // Filter by status
        if (!empty($search['active'])) {
            $query->where('is_active', 1);
        } elseif (!empty($search['inactive'])) {
            $query->where('is_active', 0);
        }
    
        // Filter by verification status
        if (!empty($search['verified'])) {
            $query->where('is_verified', 1);
        } elseif (!empty($search['unverified'])) {
            $query->where('is_verified', 0);
        }
    
        // Filter by gender
        if (!empty($search['male'])) {
            $query->where('gender', 'male');
        } elseif (!empty($search['female'])) {
            $query->where('gender', 'female');
        }
    
        $users_list = $query->select(['id', 'first_name', 'last_name', 'email', 'is_active', 'is_verified', 'gender', 'created_at', 'updated_at', 'date_of_birth', 'phone_number', 'address', 'city', 'state', 'country', 'postal_code'])
        ->paginate($perPage, ['*'], 'page', $page);
    
        return response()->json([
            'success' => true,
            'total_count' => $users_list->total(),
            'pagination' => [
                'current_page' => $users_list->currentPage(),
                'last_page' => $users_list->lastPage(),
                'per_page' => $users_list->perPage(),
                'total' => $users_list->total(),
            ],
            'userslists' => $users_list->items()
        ], 200);
    }

    public function deactivateUserAccount(Request $request)
    {
        if (empty($request->user_id) || $request->user_id == null || $request->user_id == '' || $request->user_id == 'null') {
            return response()->json([
                'success' => 'false',
                'message' => 'User ID is required. Please select a valid User, thank you!'
            ], 200);
        }

        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json([
                'success' => 'false',
                'message' => 'User not found. Please check the User ID and try again.'
            ], 200);
        }

        if(empty($request->action) || $request->action == null || $request->action == '' || $request->action == 'null') {
            return response()->json([
                'success' => 'false',
                'message' => 'Action not recognized. Please try again or contact support if the issue persists.'
            ], 200);
        }

        $user->is_active = $request->action == 'activate' ? 1 : 0;
        $user->updated_at = now();
        $user->save();

        return response()->json([
            'success' => 'true',
            'message' => $user->is_active == 1 ? 'User account has been successfully activated.' : 'User account has been successfully deactivated.'
        ], 200);
    }

       



}
