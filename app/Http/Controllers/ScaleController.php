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
            Log::error('Creation of scale failed', ['scale'=> $errors]);
            return response()->json([
                'error' => $errors
            ]);
        }

        $data = $validator->validated();

        Scale::create($data);

        $newScale = $data['scale'];
        Log::info('Scale created', ['scale'=> $data]);
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
                Log::error('Update of scale failed', ['scale'=> $errors]);
                return response()->json([
                    'error' => $validator->errors()
                ]);
            }
    
            $data = $validator->validated();
        $scale->update($data);

        $newscale = $data['scale'];
        Log::info('Scale created', ['scale'=> $data]);
        return response()->json(['message' => "Your scale was updated! The said scale is now $newscale"], 200);
    }

    public function destroy(Scale $scale){
        $scale->delete();
        Log::info('Scale deleted', ['scale' => $scale]);
        return response()->json(['message' => "Delete is done!"], 200);
    }
}
