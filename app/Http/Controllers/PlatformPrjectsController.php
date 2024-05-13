<?php

namespace App\Http\Controllers;

use App\Models\Platform_prjects;
use App\Models\Project_parts;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class PlatformPrjectsController extends Controller
{
    public function index()
    {
        $lessons = Platform_prjects::with('user')->get();
        if($lessons->isEmpty()){
            return response()->json('no lessons found',202);
        }else{
            $lessons = $lessons->map(function ($lesson) {
                    $lesson->updated_at = \Carbon\Carbon::parse($lesson->updated_at)->format('Y-m-d');
                    $lesson->created_at = \Carbon\Carbon::parse($lesson->created_at)->format('Y-m-d');
                    $lesson->username = $lesson->user->name;
                    return $lesson;
                });
        return response()->json($lessons , 200);

        }
    }
    public function edit($id){
        // return response()->json($id , 200);
        $lessons = Platform_prjects::where('id' , $id)->get();
    $lessonParts = Project_parts::where('lesson_id', $id)->get();
    if(!$lessonParts->isEmpty()){
        return response()->json([
            'lessons'=> $lessons,
           'lessonParts'=> $lessonParts
            ] , 200);
    }else{
        return response()->json('no parts found for this project' , 404);

    }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
    if ($request->hasFile('image')) {
        $photoName = Str::random().'.'.$request->image->getClientOriginalExtension();
        // using storage first u need to run code we mentioned above 
        // thats code means get in puplic folder and putfileAs mean makefolder
        // and after comma mean save that file comma with this name
        Storage::disk('public')->putFileAs('projects/images/',$request->image ,$photoName);
    $lesson = Platform_prjects::create([
        'title' => $request->input('title'),
        'description' => $request->input('description'),
        'user_id' => 1,
        'image' => $photoName
    ]);
    Project_parts::create([
        'content' => $request->input('content'),
        'part' => $request->input('part'),
        'lesson_id' => $lesson->id
    ]);
    
    return response()->json( 'Lesson created successfully with image', 201);

    }
}catch (Exception $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
}
public function parts(Request $request){
    $id = Platform_prjects::where('user_id', 1)->latest('id')->pluck('id')->first();
    try{
        Project_parts::create([
            'content' => $request->input('content'),
            'part' => $request->input('part'),
            'lesson_id' => $id,
        ]);
    return response()->json('Lesson created successfully', 201);

    }catch (Exception $e) {
        return response()->json(['message' => 'in parts'+$e], 504);
    }
}

    public function update(Request $request,$id)
    {
try {
    $lesson = Platform_prjects::find($id);

    if (!$lesson) {
        return response()->json(['message' => 'العنصر غير موجود'], 404);
    }

    $lesson->title = $request->input('title');
    $lesson->description = $request->input('description');

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('images', 'public');
        $lesson->image = $imagePath;
    }else{
        $lesson->image ='no image';
    }

    $lesson->save();

    return response()->json(['message' => 'تم تحديث العنصر بنجاح'], 201);

} catch (Exception $e) {
    return response()->json(['message' => $e->getMessage()]);
}
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $lesson = Platform_prjects::find($id);
    
            if (!$lesson) {
                return response()->json(['message' => 'العنصر غير موجود'], 404);
            }
          $les =  Project_parts::where('lesson_id', $id);
          if($les){
            $les->delete();
          }
            $lesson->delete();
    
            return response()->json(['message' => 'تم حذف العنصر بنجاح'], 200);
    
        } catch (Exception $e) {
            return response()->json(['message' => $e], 500);
        }
    }
    
}
