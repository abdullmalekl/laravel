<?php

namespace App\Http\Controllers;

use App\Models\Projects;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $id = Auth::id();
        $projects = Projects::select('id','name')->where('user_id' , $id)->get();
        if(!$projects->isEmpty()){
        return response()->json($projects);
        }else{
            return response()->json('no' , 202);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // we need to use auth()->user()->id;
        try {
            $request->validate([
                'name' =>  [
                    'required',
                    function ($attribute, $value, $fail) {
                        if (preg_match('/<script>/', $value)) {
                            $fail($attribute.' contains invalid content.');
                        }
                    },
                    ],
                    // 'user_id' => 'required' || enable it when use Auth()
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 203);
        }
            $id = Auth::id();
            $project = new Projects;
            $project->name = $request->name;
            $project->user_id = $id;
            $project->save();
            return response()->json($project);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $project = Projects::with('user')->find($id);
        if ($project) {
        return response()->json([
            'projectName' => $project->name,
            'userName' => $project->user->name,
        ]);
        } else {
            return response()->json(['message' => 'لا يوجد مشروع'], 204);
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Projects $project)
    {
        try {
           $request->validate([
                'name' =>  [
                    'required',
                    function ($attribute, $value, $fail) {
                        if (preg_match('/<script>/', $value)) {
                            $fail($attribute.' contains invalid content.');
                        }
                    },
                ]
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 201);
        }
        if (!$project) {
            return response()->json(['message' => 'لم يتم ايجاد المشروع'], 202);
        }else{
            $project->name = $request->input('name');
            $project->save();
            return response()->json($project);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try{
            $project = Projects::find($id);
            if(!$project){
                return response()->json('not Found!' , 204);
            }
            $project->delete();
        return response()->json('تم حذف المشروع بنجاح',200);
        }catch(\Exception){
            return response()->json('not ok!',201);
        }
    }
}
