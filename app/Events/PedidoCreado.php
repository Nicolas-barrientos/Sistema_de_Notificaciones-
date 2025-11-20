<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PedidoCreado implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pedido;
    public $userId;

    public function __construct($pedido, $userId)
    {
        $this->pedido = $pedido;
        $this->userId = $userId;
        
        Log::info('🔥 Evento PedidoCreado creado', [
            'pedido' => $pedido,
            'userId' => $userId
        ]);
    }

    public function broadcastOn()
    {
        Log::info('📡 Broadcasting en canal: user.' . $this->userId);
        return new PrivateChannel('user.' . $this->userId);
    }
}