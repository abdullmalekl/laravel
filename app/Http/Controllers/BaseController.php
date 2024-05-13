<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller as Controller;
// use Illuminate\Http\Request;

class BaseController extends Controller
{
    public  function sendResponse($result , $message ){
        $res = [
            'success' => true,
            'message' => $message,
            'data' => $result
        ];
        return response()->json($res , 200);
    }
    public  function sendError($error, $errorMessage = [] , $code){
        $res = [
            'success' => false,
            'data' => $error
        ];
        if(!empty($errorMessage)){
            $res['data'] = $errorMessage;
        }
        return response()->json($res , $code);
    }
}
