<?php

namespace App\Events;

use App\Models\Toast;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ToastSent implements ShouldBroadcastNow
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

    /**
     * Without this, Laravel broadcasts using the full class name
     * (App\Events\ToastSent), which never matches the frontend's
     * channel.bind('ToastSent', ...) call.
     */
    public function broadcastAs()
    {
        return 'ToastSent';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->toast->id,
            'message' => $this->toast->message,
            'type' => $this->toast->type,
            'duration' => $this->toast->duration,
            'is_read' => $this->toast->is_read,
            'created_at' => $this->toast->created_at->diffForHumans()
        ];
    }
}