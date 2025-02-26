<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request){
        $data = User::query();

        //TODO: query params

        if ($request->query('per_page') === 'all') {
            return response()->json($data->get()); 
        } else {
            $perPage = $request->query('per_page', 10); 
            return response()->json($data->paginate($perPage));
        }
    }

    public function show(User $user){
        $data = User::find($user);
        return response()->json($data);
    }
    
    public function destroy(User $user){
        $user->delete();
        return response()->json(['message' => "Delete is done!"], 200);
    }
}
