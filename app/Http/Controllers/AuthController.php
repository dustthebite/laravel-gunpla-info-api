<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirmed_password' => 'required|same:password'
        ]);

        if($validator->fails()) {
            return response()->json([
               'message' => 'validation error',
               'data' => $validator->errors()->all()    
            ], 500);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $data = [];
        $data['token'] = $user->createToken('UserToken')->accessToken;
        $data['username'] = $user->name;
        $data['email'] = $user->email;

        return response()->json([
            'message' => 'registration succesfull',
            'data' => $data
        ], 200);
    }

    public function login(Request $request){
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();

            $data = [];
            $data['token'] = $user->createToken('UserToken')->accessToken;
            $data['username'] = $user->name;
            $data['email'] = $user->email;


            return response()->json([
                'message' => 'login succesfull',
                'data' => $data
            ], 200);
        }

        return response()->json([
            'message' => 'login error'   
         ], 500);
    }

    public function logout()
    {
        $user = Auth::user();

        if ($user) {
            $user->token()->revoke(); 
            return response()->json([
                'message' => 'Logout successful'
            ], 200);
        }

        return response()->json([
            'message' => 'User not authenticated'
        ], 401);
    }
}