<?php

namespace App\Http\Controllers;

use App\Models\boards;
use App\Models\controllers;
use App\Models\readers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class BoardsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // this is for all boards based on some project
    public function index()
    {
        // this func to get all devices based on user took from Auth->user()->id
        $id = Auth::id();
        $boards = boards::with('project', 'user')->where('user_id' , $id)->get();
        if(!$boards->isEmpty()) 
        {
            $formattedProjects = $boards->map(function ($board) {
                return [
                    'id'=> $board->id,
                    'deviceName' => $board->deviceName,
                    'projectName' => $board->project->name
                ];
            });
        return response()->json($formattedProjects);
        }else{
            return response()->json('no',202);

            
        }
    }
    // this to get all devices to show in dashboard3
    public function dashIndex()
    {
        
        $boards = boards::with('project', 'user')->get();
        if(!$boards->isEmpty()) 
        {
            $formattedProjects = $boards->map(function ($board) {
                return [
                    'id'=> $board->id,
                    'deviceName' => $board->deviceName,
                    'projectName' => $board->project->name,
                    'userName' => $board->user->name,
                ];
            });
        return response()->json($formattedProjects , 200);
        }else{
            return response()->json('no devices found', 202);

            
        }
    }
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
                    'password' => 'required',
                    'c_password' => 'required|same:password',
                    'project_id' => 'required',
                    // 'user_id' => 'required' || enable it when use Auth()
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        }
        $id = Auth::id();
        $input = $request->all();
        $board = new boards;
        $board->deviceName = $request->name;
        $input['password'] = Hash::make($input['password']);
        $board->Auth_code = $input['password'];
        $board->project_id = $request->project_id;
        $board->user_id = $id;    
        $board->save(); 

        $data = $board->toArray();
        unset($data['user_id']);
        unset($data['project_id']);
        unset($data['Auth_code']);
        unset($data['updated_at']);
        unset($data['created_at']);
        $data['projectName'] = $request->projectName;
return response()->json($data);
        // return response()->json($board);
    }

    public function update(Request $request, $id)
    {
        $board = boards::find($id);
        // name , project_id , user_id from Auth() , to change pass inject => password , c_password
         if (!$board) {
             return response()->json(['message' => 'لم يتم ايجاد الجهاز'], 203);
         }else{
            $data = [];
            // if we need to change from cloud all info
            if($request->has('name')){
                $data['deviceName'] = $request->input('name');
            }
            if($request->has('project_id')){
                $data['project_id'] = $request->input('project_id');
            }
            //  if he need to change the password from cloud
             if($request->Auth_code != ''){
                 if(Hash::check($request->Auth_code, $board->Auth_code)){
                    if($request->password != $request->c_password) {
                        return response()->json(['message' => 'Password confirmation does not match'], 201);
                    }else{
                        $input = $request->password;
                        $input = Hash::make($input);
                        $data['Auth_code'] = $input;
                    }
                    }else{
                        return response()->json(['message' => 'Password Incorrect'], 202);
                    }    
            }
            //  if he need to change the password from dashboard so we dont need to old pass
            if($request->has('d_password')) {
                if($request->d_password != $request->c_password) {
                    return response()->json(['message' => 'Password confirmation does not match'], 220);
                }else{
                $input = $request->d_password;
                $input = Hash::make($input);
                $data['Auth_code'] = $input;
                }  
        }
        boards::where('id', $id)->update($data);
        unset($data['user_id']);
        unset($data['project_id']);
        unset($data['Auth_code']);
        unset($data['updated_at']);
        unset($data['created_at']);
        $data['id'] = $id;
        $data['projectName'] = $request->projectName;
   return response()->json($data ,200);
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        controllers::where('board_id' , $id)->delete();
        readers::where('board_id' , $id)->delete();
        $board = boards::find($id);
        $board->delete();
        return response()->json('تم حذف الجهاز بنجاح',200);
    }
}
