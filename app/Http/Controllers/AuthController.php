<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
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

    public function logout(){
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

    public function sendResetLink(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);

        if($validator->fails()) {
            return response()->json([
               'message' => 'Invalid email',
               'data' => $validator->errors()->all()    
            ], 500);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $token = Password::getRepository()->create($user);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );

        return response()->json([
            'message' => 'Password reset token generated',
            'token' => $token 
        ]);
    }

    public function resetPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
            'token' => 'required'
        ]);
        
        if($validator->fails()){
            return response()->json([
                'message' => 'Validation error',
                'data' => $validator->errors()->all() 
            ], 500);
        }
        
        $reset = DB::table('password_reset_tokens')->where('email', $request->email)->where('token', $request->token)->first();

        if (!$reset) {
            return response()->json(['error' => 'Invalid token'], 400);
        }
        
        $user = User::where('email', $request->email)->firstOrFail();
        $user->update(['password' => bcrypt($request->password)]);
        $user->tokens()->delete();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Password has been reset']);
    }
}