<?php

namespace App\Http\Controllers;

use App\Events\board;
use App\Events\Distance;
use App\Events\Gas;
use App\Events\Humidity;
use App\Events\Light;
use App\Events\Smoke;
use App\Events\Switchable;
use App\Events\Temprature;
use App\Models\boards;
use App\Models\controllers;
use App\Models\readers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Exception;
class CloudController extends Controller
{

    
    public function control(Request $request){
     try{
        if($request->has('light')){
            event(new Light($request->light));
         $light= controllers::find($request->id);
         $light->status = $request->light;
         $light->save();
        }
        if($request->has('status')){
            event(new Switchable($request->status));
            $switch= controllers::find($request->id);
            $switch->status = $request->status;
            $switch->save();
        }
     }catch(Exception $e){
        return response()->json($e->getMessage(),202);
     }
    }
    public function show(Request $request){
        try{
            $board = boards::find($request->publicKey);
            if ($board){
                if (Hash::check($request->secretKey, $board->Auth_code)) {
                    // event trigger to show board status is on
                    event(new board('on'));
                    $readers = readers::where('board_id' ,$request->publicKey)->get();
                    if($request->has('Temprature')){
                        foreach($readers as $reader){
                            if($reader->sensor_id == 2){ 
                                // trigger temprature event
                                event(new Temprature($request->Temprature));
                                $reader->data = $request->Temprature;
                                $reader->save();
                            }
                        }
                    }
                    if($request->has('Humidity')){
                        foreach($readers as $reader){
                            if($reader->sensor_id == 3){ 
                                // trigger Humidity event
                                event(new Humidity($request->Humidity));
                                $reader->data = $request->Humidity;
                                $reader->save();
                            }
                        }
                    }
                    if($request->has('Distance')){
                        foreach($readers as $reader){
                            if($reader->sensor_id == 4){ 
                                // trigger Distance event
                                event(new Distance($request->Distance));
                                $reader->data = $request->Distance;
                                $reader->save();
                            }
                        }
                    }
                    if($request->has('Smoke')){
                        foreach($readers as $reader){
                            if($reader->sensor_id == 6){ 
                                // trigger Smoke event
                                event(new Smoke($request->Smoke));
                                $reader->data = $request->Smoke;
                                $reader->save();
                            }
                        }
                    }
                    if($request->has('Gas')){
                        foreach($readers as $reader){
                            if($reader->sensor_id == 7){ 
                                // trigger Gas event
                                event(new Gas($request->Gas));
                                $reader->data = $request->Gas;
                                $reader->save();
                            }
                        }
                    }
                   $controllers = controllers::select('pin', 'status')
                   ->where('board_id' , $request->publicKey)
                   ->get()->pluck('status', 'pin');
                    return response()->json($controllers,200);
                } else {
                    return response()->json('UnAuthorized!', 401);
                }
            } else {
                return response()->json('no boards', 404);
            }
        }catch(Exception $e){
           return response()->json($e->getMessage(),202);
        }
       }
}
