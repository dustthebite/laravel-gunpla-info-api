<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GradeController extends Controller
{
    public function index(Request $request){
        $data = Grade::query();
        if ($request->query('per_page') === 'all') {
            return response()->json($data->get()); 
        } else {
            $perPage = $request->query('per_page', 10); 
            return response()->json($data->paginate($perPage));
        }
    }

    public function store(Request $request){
            $validator = Validator::make($request->all(), [
                'grade' => 'required'
            ]);
            if($validator->fails()){
                $errors = $validator->errors();
                Log::error('Creation of grade failed', ['grade'=> $errors]);
                return response()->json([
                    'error' => $errors 
                ]);
            }
    
            $data = $validator->validated();
    

        Grade::create($data);

        $newGrade = $data['grade'];
        Log::info('Grade created', ['grade'=> $data]);
        return response()->json(['message' => "Your grade was created! The said grade is $newGrade"], 200);
    }

    public function show(Grade $grade){
        $data = Grade::find($grade);
        return response()->json($data);
    }

    public function update(Grade $grade, Request $request){
            $validator = Validator::make($request->all(), [
                'grade' => 'required'
            ]);
            if($validator->fails()){
                $errors = $validator->errors();
                Log::error("Update of grade failed", ['errors' => $errors]);
                return response()->json([
                    'error' => $validator->errors()
                ]);
            }
    
            $data = $validator->validated();
        $grade->update($data);

        $newgrade = $data['grade'];
        Log::info('Grade updated', ['updates'=> $data]);
        return response()->json(['message' => "Your grade was updated! The said grade is now $newgrade"], 200);
    }

    public function destroy(Grade $grade){
        $grade->delete();
        Log::info('Grade deleted', ['grade' => $grade]);
        return response()->json(['message' => "Delete is done!"], 200);
    }
}
