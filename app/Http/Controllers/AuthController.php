<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class AuthController extends BaseController
{
    public function index()
    {
        $users = User::all();
        if($users){
            return response()->json($users, 200);
        }else{
            return response()->json('no',204);
        }
        
    }
    public function menow()
    {
        return response()->json(auth()->id());
    }
    
    public function reset(Request $request)
    {
        $user = User::find($request->id);
        if($user){
            $hashing =  Hash::make($request->password);
            $user->password = $hashing;
            $user->save();
            return response()->json('ok',200);
        }else{
            return response()->json('no',204);

        }
    }
    public function destroy($id)
    {
        $user = User::find($id);
        if($user){
            try{
                $user->delete();
          }catch(\Exception){
            return response()->json('no',204);
            }
            return response()->json('ok',200);
            }
            
        
        }
    
    public function register(Request $request){
        
       try{
        $validator = Validator::make($request->all() , [
            'name' => 'required',
            'email' => 'required | email' ,
            'password' => 'required'
        ]);
        if($validator->fails()){
            return  $this->sendError('please validate ur info' , $validator->errors() , 203);
        }else{
            $input = $request->all();
            $input['password'] = Hash::make($input['password']);
            if($request->email == 'malek.khrwat@gmail.com'){
                $input['role'] = 'owner';
            }
            $user = User::create($input);
            return $this->sendResponse($user, 200);
        }
       }catch(\Exception){
        return response()->json('no',204);
       }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email' , 'password'))) {
           return response()->json([
            'message' => 'invalid credentials'
           ], 201);
        }
        $user = Auth::user();
        $token = $user->createToken('5715461hpcorei7')->plainTextToken;
        $cookie = cookie('jwt', $token , 60*72 , secure: true);
        return response($user , 200)->withCookie($cookie);
        // to support using cookie u need to go config/cors.php and set credentials to true
        // and to config/session.php and cors.php add this line 'same_site' => 'none',
        //  to allow the browser save the cookie jwt
    }
    function update(Request $request)
    {
       try{
        $id =  Auth::id();
        $user = User::find($id);
         if ($request->name) {
             $user->name = $request->name;
         }
     
         if (!empty($request->lastName)) {
             $user->last_name = $request->lastName;
         }
     
         if (!empty($request->password)) {
             // Compare passwords
             if (Hash::check($request->password, $user->password)) {
                 $encryptedPassword = Hash::make($request->newPassword);
                 $user->password = $encryptedPassword;
             }else{
                return response()->json('not match' , 201);
             }
         }
         if ($request->hasFile('image')) {
            $photoName = Str::random().'.'.$request->image->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('profile/images/',$request->image ,$photoName);
            $user->image = $photoName;
         }
         $user->save();
         return response()->json($user , 200);
       }catch(\Exception){
        return response()->json('error!' , 204);

       }
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
       $user =  Auth::user();
        if(!$user){
            return response()->json('not authorized',202);
        }else{
            return response()->json($user,200);
        }
        
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $cookie = Cookie::forget('jwt');

        return response('Successfully logged out',200)->withCookie($cookie);
    }

    // protected function respondWithToken($token)
    // {
    //     return response()->json([
    //         'access_token' => $token,
    //         'expires_in' => auth()->factory()->getTTL() * 60,
    //         'user' => auth()->user()
    //     ]);
    // }

}
// use \Laravel\Sanctum\NewAccessToken;

// class AuthController extends BaseController
// {
    // public function register(Request $request){
        
    //     $validator = Validator::make($request->all() , [
    //         'name' => 'required',
    //         'email' => 'required | email' ,
    //         'password' => 'required'
    //     ]);
    //     if($validator->fails()){
    //         return  $this->sendError('please validate ur info' , $validator->errors() , 422);
    //     }else{

    //         $input = $request->all();
    //         $input['password'] = Hash::make($input['password']);
    //         $user = User::create($input);
    //         return $this->sendResponse($user , 'User Registred Successfully!');
    //     }
    // }

//     public function login(Request $request){
//         if(!Auth::attempt(['email' => $request->email , 'password'=> $request->password])){
//             return $this->sendError('check ur identity info' , ['error' => 'unAuthorized'] , 401);
//         }else{
//         $user = Auth::user();
//         $id = $user->id;
//         $us = User::find($id);
//         // here we use sanctum to create token
//         $token = $us->createToken('malek571')->plainTextToken;
//         // here we will create jwt and save it to cookie
//         $cookie = cookie('jwt' , $token , 60*24);
//          return response()->json([
//             'message' => 'login Success',
//          ])->withCookie($cookie);
//         //  here we send Cookie to the browser and save it to the request to save it in server
//         //  look to Middleware Folder  in Auhenticate.php in handle function to know more
//         //  but before use this method u need to go to Config/cors.php and change the
//         //   'supports_credentials' => false, to  'supports_credentials' => true,
//         }
//     }
//     public function user(){
//         $user =Auth::user();
//         return response()->json([
//             'message' => 'User retrievd succfully',
//             'user' => $user
//          ]);
//     }
//     public function logout(){
//         $cookie = Cookie::forget('jwt');
//         return response([
//             'message' => 'logout succesfully!'
//         ])->withCookie($cookie);
//     }
// }