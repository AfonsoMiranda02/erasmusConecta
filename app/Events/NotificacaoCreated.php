<?php

namespace App\Events;

use App\Models\notificacoes;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificacaoCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notificacao;

    /**
     * Create a new event instance.
     */
    public function __construct(notificacoes $notificacao)
    {
        $this->notificacao = $notificacao;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->notificacao->user_id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'NotificacaoCreated';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->notificacao->id,
            'titulo' => $this->notificacao->titulo,
            'mensagem' => $this->notificacao->mensagem,
            'is_seen' => $this->notificacao->is_seen,
            'created_at' => $this->notificacao->created_at->toISOString(),
            'created_at_human' => $this->notificacao->created_at->diffForHumans(),
        ];
    }
}

