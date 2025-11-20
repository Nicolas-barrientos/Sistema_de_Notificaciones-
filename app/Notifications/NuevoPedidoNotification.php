<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NuevoPedidoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $pedido;

    public function __construct($pedido)
    {
        $this->pedido = $pedido;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'mensaje' => 'Tienes un nuevo pedido!',
            'pedido' => $this->pedido,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'mensaje' => 'Tienes un nuevo pedido!',
            'pedido' => $this->pedido,
        ]);
    }
}
