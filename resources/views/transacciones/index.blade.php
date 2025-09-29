{{-- resources/views/transacciones/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tesorería') }}
        </h2>
    </x-slot>

    {{-- ANTES: bg-slate-100 --}}
    <div class="py-12 bg-base-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Resumen (cards) --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-6">
                <div class="bg-white p-5 sm:p-6 rounded-lg shadow-md">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-700">Total Ingresos</h3>
                    {{-- ANTES: text-emerald-600 --}}
                    <p class="text-2xl sm:text-3xl font-bold mt-2 text-success-600 tabular-nums">
                        ${{ number_format($ingresos, 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-white p-5 sm:p-6 rounded-lg shadow-md">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-700">Total Egresos</h3>
                    {{-- ANTES: text-red-600 --}}
                    <p class="text-2xl sm:text-3xl font-bold mt-2 text-danger-600 tabular-nums">
                        ${{ number_format($egresos, 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-white p-5 sm:p-6 rounded-lg shadow-md">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-700">Balance Actual</h3>
                    {{-- ANTES: text-emerald-600 --}}
                    <p class="text-2xl sm:text-3xl font-bold mt-2 text-success-600 tabular-nums">
                        ${{ number_format($balance, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            {{-- Acciones --}}
            <div class="mb-6 flex flex-col sm:flex-row sm:flex-wrap gap-3 sm:gap-4">
                {{-- ANTES: bg-emerald-600, hover:bg-emerald-700, focus:ring-emerald-300 --}}
                <a href="{{ route('transacciones.create', ['tipo' => 'Ingreso']) }}"
                   class="px-5 py-2.5 text-sm font-medium text-white bg-success-600 hover:bg-success-700 focus:ring-4 focus:outline-none focus:ring-success-300 rounded-lg text-center"
                   role="button" aria-label="Registrar ingreso">
                    Registrar Ingreso
                </a>

                {{-- ANTES: bg-red-600, hover:bg-red-700, focus:ring-red-300 --}}
                <a href="{{ route('transacciones.create', ['tipo' => 'Egreso']) }}"
                   class="px-5 py-2.5 text-sm font-medium text-white bg-danger-600 hover:bg-danger-700 focus:ring-4 focus:outline-none focus:ring-danger-300 rounded-lg text-center"
                   role="button" aria-label="Registrar egreso">
                    Registrar Egreso
                </a>
                
                {{-- ANTES: <a> con clases codificadas --}}
                <a href="{{ route('transacciones.exportar') }}" role="button" aria-label="Exportar transacciones a Excel">
                    <x-secondary-button type="button" class="w-full justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Exportar a Excel
                    </x-secondary-button>
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">
                    {{-- Alerts --}}
                    @if (session('success'))
                        {{-- ANTES: bg-emerald-100 border-emerald-400 text-emerald-700 --}}
                        <div class="bg-success-100 border border-success-400 text-success-700 px-4 py-3 rounded relative mb-4" role="status" aria-live="polite">
                            <strong class="font-bold">¡Éxito!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        {{-- ANTES: bg-red-100 border-red-400 text-red-700 --}}
                        <div class="bg-danger-100 border border-danger-400 text-danger-700 px-4 py-3 rounded relative mb-4" role="alert" aria-live="assertive">
                            <strong class="font-bold">¡Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <h3 class="text-lg font-semibold mb-4">Historial de Transacciones</h3>

                    {{-- Tabla (solo md+) --}}
                    <div class="hidden md:block">
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white table-fixed" role="table" aria-label="Tabla de transacciones">
                                {{-- ANTES: bg-emerald-800 --}}
                                <thead class="bg-primary-800 text-white">
                                    <tr>
                                        <th scope="col" class="py-3 px-4 font-semibold text-sm text-left w-[14%]">Fecha</th>
                                        <th scope="col" class="py-3 px-4 font-semibold text-sm text-left w-[34%]">Descripción</th>
                                        <th scope="col" class="py-3 px-4 font-semibold text-sm text-left w-[20%]">Socio Aportante</th>
                                        <th scope="col" class="py-3 px-4 font-semibold text-sm text-right w-[14%]">Monto</th>
                                        <th scope="col" class="py-3 px-4 font-semibold text-sm text-left w-[10%]">Registrado por</th>
                                        <th scope="col" class="py-3 px-4 font-semibold text-sm text-left w-[8%]">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-700">
                                    @forelse ($transacciones as $transaccion)
                                        {{-- ANTES: hover:bg-slate-50 --}}
                                        <tr class="border-b hover:bg-base-50">
                                            <td class="py-3 px-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($transaccion->fecha)->format('d/m/Y') }}</td>
                                            <td class="py-3 px-4 max-w-0"><div class="truncate" title="{{ $transaccion->descripcion }}">{{ $transaccion->descripcion }}</div></td>
                                            <td class="py-3 px-4 max-w-0"><div class="truncate text-sm text-gray-600" title="{{ $transaccion->socio->nombre ?? '' }}">{{ $transaccion->socio->nombre ?? 'N/A' }}</div></td>
                                            {{-- ANTES: text-emerald-600, text-red-600 --}}
                                            <td class="py-3 px-4 font-medium whitespace-nowrap text-right tabular-nums {{ $transaccion->tipo == 'Ingreso' ? 'text-success-600' : 'text-danger-600' }}">
                                                {{ $transaccion->tipo == 'Ingreso' ? '+' : '-' }} ${{ number_format($transaccion->monto, 0, ',', '.') }}
                                            </td>
                                            <td class="py-3 px-4 whitespace-nowrap">{{ $transaccion->user->name }}</td>
                                            <td class="py-3 px-4">
                                                <div class="flex items-center gap-2 whitespace-nowrap">
                                                    {{-- ANTES: text-amber-600 hover:text-amber-700 --}}
                                                    <a href="{{ route('transacciones.edit', $transaccion->id) }}" class="text-accent-600 hover:text-accent-700 font-medium">Editar</a>
                                                    <span class="text-gray-300">|</span>
                                                    <form action="{{ route('transacciones.destroy', $transaccion->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        {{-- ANTES: text-red-600 hover:text-red-700 --}}
                                                        <button type="submit" class="text-danger-600 hover:text-danger-700 font-medium" onclick="return confirm('¿Estás seguro de que quieres eliminar esta transacción?')">Eliminar</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="py-6 text-center text-gray-500">No hay transacciones registradas aún.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Tarjetas (móvil) --}}
                    <div class="md:hidden space-y-3">
                        @forelse ($transacciones as $transaccion)
                            <article class="bg-white border rounded-lg p-4 shadow-sm">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold">{{ \Carbon\Carbon::parse($transaccion->fecha)->format('d/m/Y') }}</p>
                                        <p class="mt-2 text-sm text-gray-700 break-words" title="{{ $transaccion->descripcion }}">{{ $transaccion->descripcion }}</p>
                                        <p class="mt-2 text-xs text-gray-500">Aporte de: <span class="font-medium">{{ $transaccion->socio->nombre ?? 'N/A' }}</span></p>
                                        <p class="text-xs text-gray-500">Registrado por: <span class="font-medium">{{ $transaccion->user->name }}</span></p>
                                    </div>
                                    <div class="text-right ml-2 flex-shrink-0">
                                        {{-- ANTES: text-emerald-600, text-red-600 --}}
                                        <p class="font-medium tabular-nums {{ $transaccion->tipo == 'Ingreso' ? 'text-success-600' : 'text-danger-600' }}">
                                            {{ $transaccion->tipo == 'Ingreso' ? '+' : '-' }} ${{ number_format($transaccion->monto, 0, ',', '.') }}
                                        </p>
                                        <div class="mt-2 flex items-center justify-end gap-3">
                                            {{-- ANTES: text-amber-600 hover:text-amber-700 --}}
                                            <a href="{{ route('transacciones.edit', $transaccion->id) }}" class="text-accent-600 hover:text-accent-700 text-sm">Editar</a>
                                            <form action="{{ route('transacciones.destroy', $transaccion->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                {{-- ANTES: text-red-600 hover:text-red-700 --}}
                                                <button type="submit" class="text-danger-600 hover:text-danger-700 text-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar esta transacción?')">Eliminar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="py-6 text-center text-gray-500">No hay transacciones registradas aún.</div>
                        @endforelse
                    </div>
                    
                    <div class="mt-4">{{ $transacciones->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>