<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Timeline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TimelineController extends Controller
{
    public function index(Request $request){
        $data = Timeline::query();
        if ($request->query('per_page') === 'all') {
            return response()->json($data->get()); 
        } else {
            $perPage = $request->query('per_page', 10); 
            return response()->json($data->paginate($perPage));
        }
    }

    public function create(Request $request){
            $validator = Validator::make($request->all(), [
                'timeline' => 'required'
            ]);
            if($validator->fails()){
                $errors = $validator->errors();
                return response()->json([
                    'error' => $validator->errors()
                ]);
            }

        $data = $validator->validated();

        Timeline::create($data);

        $newTimeline = $data['timeline'];
        return response()->json(['message' => "Your timeline was created! The said timeline is $newTimeline"], 200);
    }

    public function show(Timeline $timeline){
        $data = Timeline::find($timeline);
        return response()->json($data);
    }

    public function update(Timeline $timeline, Request $request){
            $validator = Validator::make($request->all(), [
                'timeline' => 'required'
            ]);
            if($validator->fails()){
                $errors = $validator->errors();
                return response()->json([
                    'error' => $errors
                ]);
            }

        $data = $validator->validated();
        
        $timeline->update($data);

        $newTimeline = $data['timeline'];
        return response()->json(['message' => "Your timeline was updated! The said timeline is now $newTimeline"], 200);
    }

    public function destroy(Timeline $timeline){
        $timeline->delete();
        return response()->json(['message' => "Delete is done!"], 200);
    }


}
