<?php

namespace App\Http\Controllers;

use App\Models\controllers;
use App\Models\readers;
use App\Models\sensors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class SensorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $id = Auth::id();
        $controllers = controllers::with('sensors' , 'boards')->where('user_id' , $id)->get();
        if(!$controllers->isEmpty()) 
        {
            $controllers = $controllers->map(function ($controller) {
                return [
                    'id' => $controller->id,
                    'name' => $controller->name,
                    'status' => $controller->status,
                    'sensorType' => $controller->sensors->id,
                    'sensorClass' => $controller->sensors->classified,
                    'board_id' => $controller->boards->id,
                    'pin' => $controller->pin,
                    'board_name'=> $controller->boards->deviceName
                ];
            });
        }
        $readers = readers::with('sensors', 'boards')->where('user_id' , $id)->get();
        if(!$readers->isEmpty()) 
        {
            $readers = $readers->map(function ($reader) {
                return [
                    'id' => $reader->id,
                    'name' => $reader->name,
                    'readerData' => $reader->data,
                    'sensorType' => $reader->sensors->id,
                    'sensorClass' => $reader->sensors->classified,
                    'board_id' => $reader->boards->id,
                    'board_name'=> $reader->boards->deviceName
                ];
            });
        }

        $data = collect($readers)->merge($controllers)->values()->toArray();

        if(!empty($data)){
            return response()->json($data, 200);
        }else{
            return response()->json('no',202);
        }
    }
    public function store(Request $request)
    {
        $id = Auth::id();
        if($request->sensorType == 1){
            // request must has [sensorType , name ,controllerType , board_id , pin]
            $controller = controllers::create([
                'name' => $request->name,
                'status' => false,
                'sensor_id' => $request->type,
                'board_id' => $request->board_id,
                'user_id' => $id,
                'pin' => $request->pin
            ]);
            $data = $controller->toArray();
            $data['sensorClass'] = $request->sensorType;
            unset($data['user_id']);
            unset($data['updated_at']);
            unset($data['created_at']);
            return response()->json($data,201);
        }
        if($request->sensorType == 2){
            $reader = readers::create([
                'name' => $request->name,
                'data' => '0',
                'sensor_id' => $request->type,
                'board_id' => $request->board_id,
                'user_id' => $id
            ]);
            $data = $reader->toArray();
            $data['sensorClass'] = $request->sensorType;
            unset($data['user_id']);
            unset($data['updated_at']);
            unset($data['created_at']);
            return response()->json($data,201);
        }
}

 
    public function update(Request $request, $id)
    {
        if($request->sensorType == 1){
            $controller = controllers::find($request->id);
            if (!$controller) {
                return response()->json(['message' => 'لم يتم ايجاد الحساس'], 202);
            }else{
                if($request->has('name')){
                    $data['name'] = $request->input('name');
                }
                if($request->has('sensor_id')){
                    $data['sensor_id'] = $request->input('type');
                }
                if($request->has('board_id')){
                    $data['board_id'] = $request->input('board_id');
                }
                if($request->has('pin')){
                    $data['pin'] = $request->input('pin');
                }
            }
           controllers::where('id', $request->id)->update($data);
           
           return response()->json('sensor updated',201);
           
        }
        if($request->sensorType == 2){
            $reader = readers::find($request->id);
            if (!$reader) {
                return response()->json(['message' => 'لم يتم ايجاد الحساس'], 202);
            }else{
                if($request->has('name')){
                    $data['name'] = $request->input('name');
                }
                if($request->has('sensor_id')){
                    $data['sensor_id'] = $request->input('type');
                }
                if($request->has('board_id')){
                    $data['board_id'] = $request->input('board_id');
                }
            }
            readers::where('id', $request->id)->update($data);
           
           return response()->json('sensor updated',201);
        }

    
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request , $id)
    {
        if($request->sensorClass == 2){
            $reader = readers::find($id);
            if(!$reader){
            return response()->json('notFound' ,204);
            }else{
                $reader->delete();
                return response()->json('sensor deleted' ,200);
            }
    }
    if($request->sensorClass == 1){
        $controller = controllers::find($id);
        if(!$controller){
            return response()->json('notFound' ,204);
            }else{
        $controller->delete();
        return response()->json('sensor deleted' ,200);
            }
}

}
}