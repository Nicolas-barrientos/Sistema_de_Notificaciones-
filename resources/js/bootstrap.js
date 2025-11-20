import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Hacer Pusher disponible globalmente para Echo
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

console.log("✅ Echo inicializado con Reverb");

// Esperar a que el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    // Obtener el ID del usuario logueado desde la meta tag
    const userIdMeta = document.querySelector('meta[name="user-id"]');
    if (!userIdMeta) {
        console.warn("No se encontró meta[name='user-id'], Echo no escuchará canales privados.");
        return;
    }

    const userId = userIdMeta.getAttribute('content');

    // Contador de notificaciones
    let contadorNotificaciones = 0;

    // Función para mostrar notificación en pantalla CON ANIMACIÓN
    function mostrarNotificacion(pedido) {
        const div = document.createElement("div");
        div.classList.add(
            'notification-item',
            'p-4', 
            'bg-gradient-to-r', 
            'from-blue-50', 
            'to-blue-100', 
            'border-l-4', 
            'border-blue-500', 
            'rounded-lg', 
            'shadow-lg',
            'mb-3',
            'transform',
            'transition-all',
            'duration-500'
        );
        
        // Inicialmente invisible y desplazado
        div.style.opacity = '0';
        div.style.transform = 'translateX(-100%) scale(0.8)';
        
        div.innerHTML = `
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-500 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-bold text-blue-900">
                        🔔 Nuevo Pedido #${pedido.id}
                    </p>
                    <p class="text-sm text-blue-700 mt-1">
                        <strong>Producto:</strong> ${pedido.producto}
                    </p>
                    <p class="text-sm text-blue-700">
                        <strong>Precio:</strong> <span class="font-semibold text-green-600">$${pedido.precio}</span>
                    </p>
                </div>
            </div>
        `;
        
        const contenedor = document.getElementById('notificaciones');
        if (contenedor) {
            contenedor.prepend(div);
            
            // Animar entrada
            setTimeout(() => {
                div.style.opacity = '1';
                div.style.transform = 'translateX(0) scale(1)';
            }, 10);
            
            // Efecto de "pulso" después de aparecer
            setTimeout(() => {
                div.classList.add('animate-pulse-once');
            }, 500);
            
            // Remover la animación de pulso
            setTimeout(() => {
                div.classList.remove('animate-pulse-once');
            }, 1500);
        }

        // Actualizar contador con animación
        contadorNotificaciones++;
        const spanContador = document.getElementById('contadorNotificaciones');
        if (spanContador) {
            spanContador.classList.add('scale-150', 'text-red-500');
            spanContador.innerText = contadorNotificaciones;
            
            setTimeout(() => {
                spanContador.classList.remove('scale-150', 'text-red-500');
            }, 300);
        }
    }

    // Inicializar Echo
    if (userId) {
        window.Echo.private(`user.${userId}`)
            .listen('PedidoCreado', (e) => {
                console.log("📦 Notificación recibida:", e);
                mostrarNotificacion(e.pedido);
            });
        console.log(`✅ Escuchando canal privado: user.${userId}`);
    } else {
        console.warn("No hay usuario logueado, Echo no se conectará a canales privados.");
    }

    // Botón de prueba para crear pedido
    const btnCrearPedido = document.getElementById('btnCrearPedido');
    if (btnCrearPedido) {
        btnCrearPedido.addEventListener('click', async () => {
            try {
                const res = await fetch('/crear-pedido');
                const text = await res.text();
                console.log(text);
            } catch (err) {
                console.error("Error al crear pedido:", err);
            }
        });
    }
});