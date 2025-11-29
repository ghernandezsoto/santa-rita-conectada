<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle del Socio') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-base-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Columna de Información Principal del Socio --}}
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">{{ $socio->nombre }}</h3>
                                <p class="mt-1 text-sm text-gray-500 font-mono">{{ $socio->rut }}</p>
                            </div>
                            {{-- Badge de Estado Estandarizado --}}
                            <span class="py-1 px-3 rounded-full text-xs font-bold {{ $socio->estado === 'Activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $socio->estado }}
                            </span>
                        </div>

                        <div class="mt-6 border-t border-gray-200 pt-6">
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Correo Electrónico</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-medium">{{ $socio->email ?? 'No registrado' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-medium">{{ $socio->telefono ?? 'No registrado' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Domicilio</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $socio->domicilio }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Fecha de Ingreso</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($socio->fecha_ingreso)->format('d/m/Y') }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="mt-8 flex flex-col gap-3">
                            {{-- Botón Editar (Amarillo para consistencia con acción de editar) --}}
                            <a href="{{ route('socios.edit', $socio->id) }}" class="w-full flex justify-center items-center gap-2 px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition shadow-sm font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                Editar Datos
                            </a>
                            {{-- Botón Volver --}}
                            <a href="{{ route('socios.index') }}" class="w-full flex justify-center items-center gap-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition shadow-sm font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Volver a la Lista
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Columna de Historial de Aportes --}}
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                Historial de Aportes y Transacciones
                            </h3>
                            
                            <div class="overflow-x-auto rounded-lg border border-gray-200">
                                <table class="min-w-full bg-white">
                                    {{-- Header estandarizado --}}
                                    <thead class="bg-primary-800 text-white uppercase text-xs leading-normal">
                                        <tr>
                                            <th class="py-3 px-4 font-semibold text-left w-1/4">Fecha</th>
                                            <th class="py-3 px-4 font-semibold text-left w-2/4">Descripción</th>
                                            <th class="py-3 px-4 font-semibold text-right w-1/4">Monto</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-600 text-sm font-light">
                                        @forelse ($socio->transacciones as $transaccion)
                                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                                <td class="py-3 px-4 whitespace-nowrap font-medium">
                                                    {{ \Carbon\Carbon::parse($transaccion->fecha)->format('d/m/Y') }}
                                                </td>
                                                <td class="py-3 px-4">
                                                    {{ $transaccion->descripcion }}
                                                </td>
                                                <td class="py-3 px-4 text-right font-medium tabular-nums {{ $transaccion->tipo == 'Ingreso' ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $transaccion->tipo == 'Ingreso' ? '+' : '-' }} ${{ number_format($transaccion->monto, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="py-8 text-center text-gray-500">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Este socio no tiene transacciones registradas.
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>