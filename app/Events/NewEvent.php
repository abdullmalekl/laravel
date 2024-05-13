<?php

namespace App\Events;
use App\Models\Comments;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $comments;
    /**
     * Create a new event instance.
     */
    public function __construct(Comments $comment)
    {
        $this->comments = $comment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // here add the hame of the channel
        return ['Comments'];
    }
    // public function broadcastWith(): array
    // {
    //     // to customize the payload , means what the payload u wanna send to front-end
    //     // u should add it because event may send user info too
    //     return [
    //         'comment' => $this->comments
    //     ];
    // }
    public function broadcastAs(){
        return 'NewEvent';
    }
}
