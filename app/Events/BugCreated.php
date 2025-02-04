<?php

namespace App\Events;

use App\Models\Bug;
use App\Models\BugComment;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BugCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Bug $bug;
    public ?User $assigned_user;

    /**
     * Create a new event instance.
     */
    public function __construct(Bug $bug, ?User $assigned_user)
    {
        $this->bug = $bug;
        $this->assigned_user = $assigned_user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
