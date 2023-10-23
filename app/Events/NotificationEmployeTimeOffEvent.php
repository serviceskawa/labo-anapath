<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationEmployeTimeOffEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $timeOff;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($userId,$timeOff)
    {
        $this->userId = $userId;
        $this->timeOff = $timeOff;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
