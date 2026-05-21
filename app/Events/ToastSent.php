<?php

namespace App\Events;

use App\Models\Toast;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ToastSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $toast;

    public function __construct(Toast $toast)
    {
        $this->toast = $toast;
    }

    public function broadcastOn()
    {
        return new Channel('toasts');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->toast->id,
            'message' => $this->toast->message,
            'type' => $this->toast->type,
            'created_at' => $this->toast->created_at->diffForHumans()
        ];
    }
}