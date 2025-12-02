<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle de Transacción') }} #{{ $transaccion->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Encabezado con Estado --}}
                    <div class="flex justify-between items-start mb-6 border-b pb-4">
                        <div>
                            <p class="text-sm text-gray-500 uppercase tracking-wide">Tipo de Movimiento</p>
                            <h3 class="text-2xl font-bold {{ $transaccion->tipo == 'Ingreso' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaccion->tipo }}
                            </h3>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500 uppercase tracking-wide">Monto</p>
                            <p class="text-3xl font-bold text-gray-800">
                                ${{ number_format($transaccion->monto, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    {{-- Grid de Datos --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Fecha</p>
                            {{-- FIX FECHA: translatedFormat con escape correcto para "de" --}}
                            <p class="text-lg">{{ $transaccion->fecha->translatedFormat('d \d\e F \d\e Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Registrado por</p>
                            <p class="text-lg">{{ $transaccion->user->name ?? 'Usuario Eliminado' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm font-medium text-gray-500">Descripción</p>
                            <p class="text-lg bg-gray-50 p-3 rounded-md border border-gray-100 mt-1">
                                {{ $transaccion->descripcion }}
                            </p>
                        </div>
                        @if($transaccion->socio)
                        <div class="md:col-span-2">
                            <p class="text-sm font-medium text-gray-500">Socio Asociado</p>
                            <div class="flex items-center gap-3 mt-1 p-3 bg-blue-50 rounded-md border border-blue-100 text-blue-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="font-bold">{{ $transaccion->socio->nombre }}</span>
                                <span class="text-sm opacity-75">({{ $transaccion->socio->rut }})</span>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Sección de Comprobante --}}
                    <div class="border-t pt-6">
                        <h4 class="font-bold text-lg mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                            Comprobante Adjunto
                        </h4>
                        
                        @if ($transaccion->comprobante_path)
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-sm text-gray-600">Archivo almacenado en servidor</span>
                                    <a href="{{ asset('storage/' . $transaccion->comprobante_path) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Descargar / Ver
                                    </a>
                                </div>
                                {{-- Previsualización si es imagen --}}
                                @php
                                    $extension = pathinfo($transaccion->comprobante_path, PATHINFO_EXTENSION);
                                @endphp
                                @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp']))
                                    <div class="mt-2 rounded overflow-hidden shadow-sm max-w-sm mx-auto">
                                        <img src="{{ asset('storage/' . $transaccion->comprobante_path) }}" alt="Comprobante" class="w-full h-auto">
                                    </div>
                                @elseif(strtolower($extension) === 'pdf')
                                    <div class="text-center py-8 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-2 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        <p>Vista previa no disponible para PDF.</p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            No se ha adjuntado ningún comprobante a esta transacción.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="mt-8 flex justify-end gap-3">
                        <a href="{{ route('transacciones.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">
                            Volver
                        </a>
                        <a href="{{ route('transacciones.edit', $transaccion->id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition shadow-sm">
                            Editar
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>