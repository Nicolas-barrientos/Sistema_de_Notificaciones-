<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Events\PedidoCreado;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // 🔥 Ruta para crear pedido de prueba
    Route::get('/crear-pedido', function () {
        $pedido = [
            'id' => rand(1000, 9999),
            'producto' => 'Producto de prueba',
            'precio' => rand(100, 1000)
        ];

        // Obtener el usuario actual usando Auth facade
        $user = Auth::user();
        
        // Disparar el evento
        event(new PedidoCreado($pedido, $user->id));

        return 'Pedido creado y notificación enviada para usuario: ' . $user->name;
    })->name('crear.pedido');
});

require __DIR__.'/auth.php';