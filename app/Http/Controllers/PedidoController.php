<?php

namespace App\Http\Controllers;

use App\Events\PedidoCreado;
use App\Models\User;
use App\Notifications\NuevoPedidoNotification;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function crear()
    {
        // Simulamos un pedido
        $pedido = [
            'id' => rand(1000, 9999),
            'producto' => 'Producto de prueba',
            'precio' => 2500,
        ];

        // Usuario al que se avisará
        $user = User::first(); // usamos el primer usuario autenticado

        // Disparar el evento
        event(new PedidoCreado($pedido, $user->id));

        // Guardar notificación en BD
        $user->notify(new NuevoPedidoNotification($pedido));

        return 'Pedido creado y notificación enviada';
    }
}
