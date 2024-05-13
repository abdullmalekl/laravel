<?php

namespace App\Http\Controllers;

use App\Models\Lessons;
use App\Models\Lesson_parts;
use Illuminate\Http\Request;
use Exception;
// use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;



class LessonsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lessons = Lessons::with('user')->get();
        
if($lessons->isEmpty()) {
    return response()->json('no lessons found', 204);
} else {
    $lessons = $lessons->map(function ($lesson) {
        $lesson->updated_at = \Carbon\Carbon::parse($lesson->updated_at)->format('Y-m-d');
        $lesson->created_at = \Carbon\Carbon::parse($lesson->created_at)->format('Y-m-d');
        $lesson->username = $lesson->user->name;
        return $lesson;
    });
    return response()->json($lessons, 200);
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
        Storage::disk('public')->putFileAs('lessons/images/',$request->image ,$photoName);
    $lesson = Lessons::create([
        'title' => $request->input('title'),
        'description' => $request->input('description'),
        'user_id' => 1,
        'image' => $photoName
    ]);

    Lesson_parts::create([
        'content' => $request->input('content'),
        'part' => $request->input('part'),
        'lesson_id' => $lesson->id
    ]);
    
    return response()->json(['message' => 'Lesson created successfully with image'], 201);

    }
    
}catch (Exception $e) {
        return response()->json(['message' => $e], 504);
    }
}
public function parts(Request $request){
    $id = Lessons::where('user_id', 1)->latest('id')->pluck('id')->first();
    try{
        Lesson_parts::create([
            'content' => $request->input('content'),
            'part' => $request->input('part'),
            'lesson_id' => $id,
        ]);
    return response()->json('Lesson created successfully', 201);

    }catch (Exception $e) {
        return response()->json(['message' => 'in parts'+$e], 204);
    }
}
public function upload_image(Request $request)
    {
        try {
            
            $fieldname = "image_param";
            
            // Get filename.
            $filename = explode(".", $_FILES[$fieldname]["name"]);
            
            // Validate uploaded files.
            // Do not use $_FILES["file"]["type"] as it can be easily forged.
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            
            // Get temp file name.
            $tmpName = $_FILES[$fieldname]["tmp_name"];
            
            // Get mime type.
            $mimeType = finfo_file($finfo, $tmpName);

            // Get extension. You must include fileinfo PHP extension.
            $extension = end($filename);
            
            // Allowed extensions.
            $allowedExts = array("gif", "jpeg", "jpg", "png", "svg", "blob");
            
            // Allowed mime types.
            $allowedMimeTypes = array("image/gif", "image/jpeg", "image/pjpeg", "image/x-png", "image/png", "image/svg+xml");
            
            // Validate image.
            if (!in_array(strtolower($mimeType), $allowedMimeTypes) || !in_array(strtolower($extension), $allowedExts)) {
            throw new Exception("File does not meet the validation.");
            }
            
            // Generate new random name.
            $name = sha1(microtime()) . "." . $extension;
            $fullNamePath = public_path('images/' . $name);
            
            // Save file in the uploads folder.
            move_uploaded_file($tmpName, $fullNamePath);
            
            // Generate response.
            
            $url = url('images/' . $name); // Get the URL of the uploaded image

        return response()->json(['link' => $url]);

            } catch (Exception $e) {
            // Send error response.
            echo $e->getMessage();
            http_response_code(404);
            }
    }
    public function edit($id){
        // return response()->json($id , 200);
        $lessons = Lessons::where('id' , $id)->get();
    $lessonParts = Lesson_parts::where('lesson_id', $id)->get();
    if(!$lessonParts->isEmpty()){
        return response()->json([
            'lessons'=> $lessons,
           'lessonParts'=> $lessonParts
            ] , 200);
    }else{
        return response()->json('no parts found for this lesson' , 404);

    }

    }
    public function update(Request $request,$id)
    {
try {
    $lesson = Lessons::find($id);

    if (!$lesson) {
        return response()->json(['message' => 'العنصر غير موجود'], 404);
    }

    $lesson->title = $request->input('title');
    $lesson->description = $request->input('description');

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('images', 'public');
        $lesson->image = $imagePath;
    }

    $lesson->save();

    $lessonPart = Lesson_parts::where('lesson_id', $id)->first();

    if ($lessonPart) {
        $lessonPart->content = $request->input('content');
        $lessonPart->save();
    }

    return response()->json(['message' => 'تم تحديث العنصر بنجاح'], 201);

} catch (Exception $e) {
    return response()->json($e->getMessage(), 500);
}
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $lesson = Lessons::find($id);
    
            if (!$lesson) {
                return response()->json(['message' => 'العنصر غير موجود'], 404);
            }
            $les =  Lesson_parts::where('lesson_id', $id);
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
