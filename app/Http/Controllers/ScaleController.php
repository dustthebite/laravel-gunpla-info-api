<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Scale;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ScaleController extends Controller
{
    public function index(Request $request){
        $data = Scale::query();
        if ($request->query('per_page') === 'all') {
            return response()->json($data->get()); 
        } else {
            $perPage = $request->query('per_page', 10); 
            return response()->json($data->paginate($perPage));
        }
    }
    
    public function store(Request $request){
        
        $validator = Validator::make($request->all(), [
            'scale' => ['required', 'regex:/^1\/\d+$/']
        ]);
        if($validator->fails()){
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ]);
        }

        $data = $validator->validated();

        Scale::create($data);

        $newScale = $data['scale'];
        return response()->json(['message' => "Your scale was created! The said scale is $newScale"], 200);
    }

    public function show(Scale $scale){
        $data = Scale::find($scale);
        return response()->json($data);
    }

    public function update(Scale $scale, Request $request){
            $validator = Validator::make($request->all(), [
                'scale' => ['required', 'regex:/^1\/\d+$/']
            ]);
            if($validator->fails()){
                $errors = $validator->errors();
                return response()->json([
                    'error' => $validator->errors()
                ]);
            }
    
            $data = $validator->validated();
        $scale->update($data);

        $newscale = $data['scale'];
        return response()->json(['message' => "Your scale was updated! The said scale is now $newscale"], 200);
    }

    public function destroy(Scale $scale){
        $scale->delete();
        return response()->json(['message' => "Delete is done!"], 200);
    }
}
