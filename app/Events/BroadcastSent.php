<?php

namespace App\Events;

use App\Models\Broadcast;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BroadcastSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Broadcast $broadcast;

    public function __construct(Broadcast $broadcast)
    {
        $this->broadcast = $broadcast;
    }

    public function broadcastOn(): array
    {
        $channels = [new Channel('broadcasts')];
        return $channels;
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->broadcast->id,
            'judul' => $this->broadcast->judul,
            'isi' => $this->broadcast->isi,
            'target_role' => $this->broadcast->target_role,
            'created_at' => $this->broadcast->created_at->toISOString(),
        ];
    }
}
