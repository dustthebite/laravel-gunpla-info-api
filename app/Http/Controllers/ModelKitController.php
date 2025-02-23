<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ModelKit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ModelKitController extends Controller
{
    public function index(Request $request){
        $data = ModelKit::query();

        if($request->has('isPBandai')){
            $data->where('isPBandai', $request->query('isPBandai'));
        }

        if ($request->has('name')) {
            $data->where('name', 'LIKE', '%' . $request->query('name') . '%');
        }

        if ($request->has('price')) {
            $data->where('recommended_price_yen', $request->query('price'));
        }

        if ($request->has('price_min')) {
            $data->where('recommended_price_yen', '>=', $request->query('price_min'));
        }

        if ($request->has('price_max')) {
            $data->where('recommended_price_yen', '<=', $request->query('price_max'));
        }

        if ($request->has('release_date')) {
            $data->whereDate('release_date', $request->query('release_date'));
        }

        if ($request->has('year')) {
            $data->whereYear('release_date', $request->query('year'));
        }

        if ($request->query('sort') === 'newest') {
            $data->orderBy('release_date', 'desc');
        }

        if ($request->query('sort') === 'oldest') {
            $data->orderBy('release_date', 'asc');
        }


        if ($request->query('per_page') === 'all') {
            return response()->json($data->get()); 
        } else {
            $perPage = $request->query('per_page', 10); 
            return response()->json($data->paginate($perPage));
        }
    }

    public function store(Request $request){
            $validator = Validator::make($request->all(), [
                'name' => 'required', 
                'height_centimeters' => 'required|numeric',
                'isPBandai' => 'required|boolean',
                'grade_id' => 'required|exists:grades,id',
                'scale_id' => 'required|exists:scales,id',
                'timeline_id' => 'required|exists:timelines,id', 
                'recommended_price_yen' => 'required|decimal',
                'release_date' => 'required|date'
            ]);
            if($validator->fails()){
                $errors = $validator->errors();
                Log::error("Creation of model kit failed", ['errors' => $errors]);
                return response()->json([
                    'error' => $errors
                ]);
            }
    
        $data = $validator->validated();

        ModelKit::create($data);
        Log::info('Model kit created', ['model_kit'=> $data]);
        return response()->json(['message' => "Your model kit was created!"], 200);
    }

    public function show(ModelKit $modelKit){
        $data = ModelKit::find($modelKit);
        return response()->json($data);
    }

    public function update(ModelKit $modelKit, Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes', 
            'height_centimeters' => 'sometimes|numeric',
            'isPBandai' => 'sometimes|boolean',
            'grade_id' => 'sometimes|exists:grades,id',
            'scale_id' => 'sometimes|exists:scales,id',
            'timeline_id' => 'sometimes|exists:timelines,id', 
            'recommended_price_yen' => 'sometimes|decimal',
            'release_date' => 'sometimes|date'
        ]);
        if($validator->fails()){
            $errors = $validator->errors();
            Log::error("Update of model kit failed", ['errors' => $errors]);
            return response()->json([
                'error' => $errors
            ]);
        }
        $data = $validator->validated();

        if(empty($request->only(['name', 'height_centimeters', 'isPBandai','grade_id','scale_id', 'timeline_id', 'recommended_price_yen']))){
            Log::error("Update of model kit failed", ['error' => 'At least one field must be provided for update']);
            return response()->json([
                'error' => 'At least one field must be provided for update'
            ]);
        }
        
        $modelKit->update($data); 
        Log::info('Model kit updated', ['updates'=> $data]);
        return response()->json(['message' => "Your model kit was updated!"], 200);
    }

    public function destroy(ModelKit $modelKit){
        $modelKit->delete();
        Log::info('Model kit deleted', ['model_kit' => $modelKit]);
        return response()->json(['message' => "Delete is done!"], 200);
    }
}
