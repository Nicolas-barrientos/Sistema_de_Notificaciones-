<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-2xl font-bold text-gray-800 mb-4">🔥 Sistema de Notificaciones en Tiempo Real</h3>

                <p class="text-gray-600 mb-4">
                    Usuario autenticado: <strong class="text-blue-600">{{ auth()->user()->name }}</strong>
                </p>

                <button id="btnCrearPedido" 
                        class="mt-2 mb-6 px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transform hover:scale-105 transition-all duration-200 shadow-lg font-semibold">
                    ✨ Crear pedido de prueba
                </button>

                <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200 inline-block">
                    <span class="text-lg text-gray-700">
                        🔔 Notificaciones: 
                        <span id="contadorNotificaciones" 
                              class="inline-flex items-center justify-center w-8 h-8 ml-2 text-white bg-red-500 rounded-full font-bold">
                            0
                        </span>
                    </span>
                </div>

                <div id="notificaciones" class="mt-4 space-y-2"></div>
            </div>
        </div>
    </div>
</x-app-layout>