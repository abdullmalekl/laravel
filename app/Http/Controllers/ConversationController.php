<?php

namespace App\Http\Controllers;

use App\Events\Conversation as EventsConversation;
use App\Models\conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $messages = conversation::with('user')->get();
        
        if($messages->isEmpty()) {
            return response()->json('no message found', 204);
        } else {
            $messages = $messages->map(function ($message) {
                $message->username = $message->user->name;
                $message->user_image = $message->user->image;
                return $message;
            });
            return response()->json($messages, 200);
                }
    }

  
    public function store(Request $request)
    {
        try{

            event(new EventsConversation('h'));
            $id = Auth::id();
            $message = conversation::create([
                'message' => $request->message,
                'user_id' => $id
            ]);
            return response()->json($message, 200);

        }catch(\Exception){
            return response()->json('there are error', 204);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(conversation $conversation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(conversation $conversation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, conversation $conversation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(conversation $conversation)
    {
        //
    }
}
