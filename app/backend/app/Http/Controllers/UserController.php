<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;

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

            $token = $user->createToken('auth_token')->plainTextToken;
            
            $result = response()->json(
                array(
                    'success' => "true",
                    'status' => 'success',
                    'message' => 'User logged in successfully',
                    'user' => $user,
                    'token' => $token
                ), 200
            );
        }
        return $result;
    }

    public function logout(Request $request){

        $user = $request->user();
        Log::info("Logged in user:", $user->toArray());

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
}
