<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tesorería') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-base-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Resumen (cards) --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-6">
                <div class="bg-white p-5 sm:p-6 rounded-lg shadow-md border border-gray-100">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-700">Total Ingresos</h3>
                    <p class="text-2xl sm:text-3xl font-bold mt-2 text-green-600 tabular-nums">
                        ${{ number_format($ingresos, 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-white p-5 sm:p-6 rounded-lg shadow-md border border-gray-100">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-700">Total Egresos</h3>
                    <p class="text-2xl sm:text-3xl font-bold mt-2 text-red-600 tabular-nums">
                        ${{ number_format($egresos, 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-white p-5 sm:p-6 rounded-lg shadow-md border border-gray-100">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-700">Balance Actual</h3>
                    <p class="text-2xl sm:text-3xl font-bold mt-2 {{ $balance >= 0 ? 'text-blue-600' : 'text-gray-800' }} tabular-nums">
                        ${{ number_format($balance, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            {{-- Acciones (Botones Superiores) --}}
            <div class="mb-6 flex flex-col sm:flex-row sm:flex-wrap gap-3 sm:gap-4">
                <a href="{{ route('transacciones.create', ['tipo' => 'Ingreso']) }}"
                   class="px-5 py-2.5 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg text-center flex items-center justify-center gap-2 shadow-sm transition"
                   role="button">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                    </svg>
                    Registrar Ingreso
                </a>

                <a href="{{ route('transacciones.create', ['tipo' => 'Egreso']) }}"
                   class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg text-center flex items-center justify-center gap-2 shadow-sm transition"
                   role="button">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                    </svg>
                    Registrar Egreso
                </a>

                {{-- CORRECCIÓN SEMÁNTICA: <a> con clases de botón, sin <button> anidado --}}
                <a href="{{ route('transacciones.exportar') }}" 
                   class="w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg flex items-center justify-center gap-2 shadow-sm transition sm:ml-auto" 
                   role="button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-green-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Exportar a Excel
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">

                    {{-- Alertas Estandarizadas --}}
                    @if (session('success'))
                        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-r" role="alert">
                            <strong class="font-bold">¡Éxito!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-r" role="alert">
                            <strong class="font-bold">¡Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Historial de Transacciones</h3>

                    {{-- Tabla (solo md+) --}}
                    <div class="hidden md:block">
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            {{-- table-fixed es CLAVE para que max-w-0 funcione --}}
                            <table class="min-w-full bg-white table-fixed w-full">
                                {{-- Header primary-800 uppercase --}}
                                <thead class="bg-primary-800 text-white uppercase text-xs leading-normal">
                                    <tr>
                                        <th scope="col" class="py-3 px-4 font-semibold text-left w-[14%]">Fecha</th>
                                        <th scope="col" class="py-3 px-4 font-semibold text-left w-[34%]">Descripción</th>
                                        <th scope="col" class="py-3 px-4 font-semibold text-left w-[20%]">Socio Aportante</th>
                                        <th scope="col" class="py-3 px-4 font-semibold text-right w-[14%]">Monto</th>
                                        <th scope="col" class="py-3 px-4 font-semibold text-left w-[10%]">Registro</th>
                                        <th scope="col" class="py-3 px-4 font-semibold text-center w-[8%]">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 text-sm font-light">
                                    @forelse ($transacciones as $transaccion)
                                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                            <td class="py-3 px-4 whitespace-nowrap font-medium text-gray-800 align-middle">
                                                {{ $transaccion->fecha->format('d/m/Y') }}
                                            </td>
                                            
                                            {{-- CELDA DESCRIPCIÓN: max-w-0 fuerza el ancho, truncate corta el texto --}}
                                            <td class="py-3 px-4 align-middle max-w-0">
                                                <div class="flex items-center gap-2">
                                                    @if($transaccion->comprobante_path)
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor" title="Comprobante adjunto">
                                                            <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd" />
                                                        </svg>
                                                    @endif
                                                    <div class="truncate w-full" title="{{ $transaccion->descripcion }}">
                                                        {{ $transaccion->descripcion }}
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- CELDA SOCIO: max-w-0 y truncate --}}
                                            <td class="py-3 px-4 align-middle max-w-0">
                                                <div class="truncate w-full" title="{{ $transaccion->socio->nombre ?? '' }}">
                                                    {{ $transaccion->socio->nombre ?? 'N/A' }}
                                                </div>
                                            </td>

                                            <td class="py-3 px-4 font-medium whitespace-nowrap text-right tabular-nums align-middle">
                                                @if($transaccion->tipo == 'Ingreso')
                                                    <span class="text-green-600">+ ${{ number_format($transaccion->monto, 0, ',', '.') }}</span>
                                                @else
                                                    <span class="text-red-600">- ${{ number_format($transaccion->monto, 0, ',', '.') }}</span>
                                                @endif
                                            </td>

                                            {{-- CELDA REGISTRO: max-w-0 y truncate --}}
                                            <td class="py-3 px-4 text-xs align-middle max-w-0">
                                                <div class="truncate w-full" title="{{ $transaccion->user->name }}">
                                                    {{ $transaccion->user->name }}
                                                </div>
                                            </td>

                                            <td class="py-3 px-4 align-middle">
                                                <div class="flex items-center justify-center gap-3">
                                                    {{-- Ver Detalle --}}
                                                    <a href="{{ route('transacciones.show', $transaccion->id) }}" title="Ver Detalle" aria-label="Ver transacción {{ $transaccion->id }}" class="text-blue-500 hover:text-blue-700 transform hover:scale-110 transition">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>
                                                    {{-- Editar --}}
                                                    <a href="{{ route('transacciones.edit', $transaccion->id) }}" title="Editar" aria-label="Editar transacción {{ $transaccion->id }}" class="text-yellow-500 hover:text-yellow-700 transform hover:scale-110 transition">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                        </svg>
                                                    </a>
                                                    {{-- Eliminar --}}
                                                    <form action="{{ route('transacciones.destroy', $transaccion->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta transacción?')" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" title="Eliminar" aria-label="Eliminar transacción {{ $transaccion->id }}" class="text-red-500 hover:text-red-700 transform hover:scale-110 transition">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="py-8 text-center text-gray-500 bg-gray-50">No hay transacciones registradas aún.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Tarjetas (móvil) --}}
                    <div class="md:hidden space-y-3">
                        @forelse ($transacciones as $transaccion)
                            <article class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-bold text-primary-800">{{ $transaccion->fecha->format('d/m/Y') }}</p>
                                        {{-- FIX MOBILE: break-words evita que una palabra larga rompa el ancho --}}
                                        <p class="mt-1 text-sm text-gray-700 font-medium break-words" title="{{ $transaccion->descripcion }}">
                                            {{ $transaccion->descripcion }}
                                        </p>

                                        @if($transaccion->socio)
                                            <p class="mt-2 text-xs text-gray-500">Aporte de: <span class="font-medium text-gray-700">{{ $transaccion->socio->nombre }}</span></p>
                                        @endif

                                        <p class="text-xs text-gray-500 mt-1">Por: <span class="font-medium">{{ $transaccion->user->name }}</span></p>
                                    </div>
                                    <div class="text-right ml-2 flex-shrink-0">
                                        <p class="font-bold text-lg tabular-nums {{ $transaccion->tipo == 'Ingreso' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $transaccion->tipo == 'Ingreso' ? '+' : '-' }} ${{ number_format($transaccion->monto, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-t border-gray-200 flex items-center justify-end gap-4">
                                    <a href="{{ route('transacciones.show', $transaccion->id) }}" class="text-blue-600 font-medium text-sm flex items-center gap-1" aria-label="Ver detalle de transacción">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        Ver
                                    </a>
                                    <a href="{{ route('transacciones.edit', $transaccion->id) }}" class="text-yellow-600 font-medium text-sm flex items-center gap-1" aria-label="Editar transacción">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                        Editar
                                    </a>
                                    <form action="{{ route('transacciones.destroy', $transaccion->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta transacción?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 font-medium text-sm flex items-center gap-1" aria-label="Eliminar transacción">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </article>
                        @empty
                            <div class="py-6 text-center text-gray-500 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                                No hay transacciones registradas aún.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4">{{ $transacciones->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>