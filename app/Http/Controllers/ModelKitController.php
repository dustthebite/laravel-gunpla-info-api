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
        $query = ModelKit::with(['timeline', 'grade', 'scale']);

        $query->when($request->timeline_id, fn ($q, $value) => $q->where('timeline_id', $value));
        $query->when($request->grade_id, fn ($q, $value) => $q->where('grade_id', $value));
        $query->when($request->scale_id, fn ($q, $value) => $q->where('scale_id', $value));
        $query->when($request->isPBandai, fn($q, $value) => $q->where('isPBandai', $value));
        $query->when($request->name, fn($q, $value) => $q->where('name', 'LIKE', '%' . $value . '%'));
        $query->when($request->price, fn($q, $value) => $q->where('recommended_price_yen', $value));
        $query->when($request->price_min, fn($q, $value) => $q->where('recommended_price_yen', '>=', $value));
        $query->when($request->price_max, fn($q, $value) => $q->where('recommended_price_yen', '<=', $value));
        $query->when($request->release_date, fn($q, $value) => $q->whereDate('release_date', $value));
        $query->when($request->year, fn($q, $value) => $q->whereYear('release_date', $value));
        $query->when($request->sort === 'newest', fn($q) => $q->orderBy('release_date', 'desc'));
        $query->when($request->sort === 'oldest', fn($q) => $q->orderBy('release_date', 'asc'));
        
        if ($request->query('per_page') === 'all') {
            return response()->json($query->get()); 
        } else {
            $perPage = $request->query('per_page', 10); 
            return response()->json($query->paginate($perPage));
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
                return response()->json([
                    'error' => $errors
                ]);
            }
    
        $data = $validator->validated();

        ModelKit::create($data);
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
            return response()->json([
                'error' => $errors
            ]);
        }
        $data = $validator->validated();

        if(empty($request->only(['name', 'height_centimeters', 'isPBandai','grade_id','scale_id', 'timeline_id', 'recommended_price_yen']))){
            return response()->json([
                'error' => 'At least one field must be provided for update'
            ]);
        }
        
        $modelKit->update($data); 
        return response()->json(['message' => "Your model kit was updated!"], 200);
    }

    public function destroy(ModelKit $modelKit){
        $modelKit->delete();
        return response()->json(['message' => "Delete is done!"], 200);
    }
}
