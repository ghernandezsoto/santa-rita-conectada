<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tesorería') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700">Total Ingresos</h3>
                    <p class="text-3xl font-bold mt-2 text-emerald-600">${{ number_format($ingresos, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700">Total Egresos</h3>
                    <p class="text-3xl font-bold mt-2 text-red-600">${{ number_format($egresos, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700">Balance Actual</h3>
                    <p class="text-3xl font-bold mt-2 text-emerald-600">${{ number_format($balance, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="mb-6 flex flex-col sm:flex-row gap-4">
                <a href="{{ route('transacciones.create', ['tipo' => 'Ingreso']) }}" class="px-5 py-2.5 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:outline-none focus:ring-emerald-300 rounded-lg text-center" role="button" aria-label="Registrar ingreso">
                    Registrar Ingreso
                </a>
                <a href="{{ route('transacciones.create', ['tipo' => 'Egreso']) }}" class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 rounded-lg text-center" role="button" aria-label="Registrar egreso">
                    Registrar Egreso
                </a>

                <a href="{{ route('transacciones.exportar') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-700 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-center" role="button" aria-label="Exportar transacciones a Excel">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    <span>Exportar a Excel</span>
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative mb-4" role="status">
                            <strong class="font-bold">¡Éxito!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">¡Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <h3 class="text-lg font-semibold mb-4">Historial de Transacciones</h3>

                    {{-- Tabla para pantallas medianas en adelante --}}
                    <div class="overflow-x-auto hidden md:block">
                        <table class="min-w-full bg-white table-fixed" role="table" aria-label="Tabla de transacciones">
                            <thead class="bg-emerald-800 text-white">
                                <tr>
                                    <th class="py-3 px-4 font-semibold text-sm text-left w-[15%]">Fecha</th>
                                    <th class="py-3 px-4 font-semibold text-sm text-left w-[30%]">Descripción</th>
                                    <th class="py-3 px-4 font-semibold text-sm text-left w-[20%]">Socio Aportante</th>
                                    <th class="py-3 px-4 font-semibold text-sm text-left w-[15%]">Monto</th>
                                    <th class="py-3 px-4 font-semibold text-sm text-left w-[10%]">Registrado por</th>
                                    <th class="py-3 px-4 font-semibold text-sm text-left w-[10%]">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($transacciones as $transaccion)
                                    <tr class="border-b hover:bg-slate-50">
                                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($transaccion->fecha)->format('d/m/Y') }}</td>
                                        <td class="py-3 px-4 max-w-0">
                                            <div class="truncate" title="{{ $transaccion->descripcion }}">{{ $transaccion->descripcion }}</div>
                                        </td>
                                        <td class="py-3 px-4 text-sm text-gray-600 truncate" title="{{ $transaccion->socio->nombre ?? '' }}">
                                            {{ $transaccion->socio->nombre ?? 'N/A' }}
                                        </td>
                                        <td class="py-3 px-4 font-medium whitespace-nowrap {{ $transaccion->tipo == 'Ingreso' ? 'text-emerald-600' : 'text-red-600' }}">
                                            {{ $transaccion->tipo == 'Ingreso' ? '+' : '-' }} ${{ number_format($transaccion->monto, 0, ',', '.') }}
                                        </td>
                                        <td class="py-3 px-4">{{ $transaccion->user->name }}</td>
                                        <td class="py-3 px-4 flex items-center gap-2">
                                            <a href="{{ route('transacciones.edit', $transaccion->id) }}" class="text-amber-600 hover:text-amber-700 font-medium">Editar</a>
                                            <span class="text-gray-300">|</span>
                                            <form action="{{ route('transacciones.destroy', $transaccion->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-700 font-medium" onclick="return confirm('¿Estás seguro de que quieres eliminar esta transacción?')">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-6 text-center text-gray-500">No hay transacciones registradas aún.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Vista en tarjeta para pantallas pequeñas (mejor UX móvil) --}}
                    <div class="md:hidden space-y-3">
                        @forelse ($transacciones as $transaccion)
                            <article class="bg-white border rounded-lg p-4 shadow-sm">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-sm font-semibold">{{ \Carbon\Carbon::parse($transaccion->fecha)->format('d/m/Y') }}</p>
                                        <p class="mt-2 text-sm truncate" title="{{ $transaccion->descripcion }}">{{ $transaccion->descripcion }}</p>
                                        <p class="mt-2 text-xs text-gray-500">
                                            Aporte de: <span class="font-medium">{{ $transaccion->socio->nombre ?? 'N/A' }}</span>
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Registrado por: <span class="font-medium">{{ $transaccion->user->name }}</span>
                                        </p>
                                    </div>
                                    <div class="text-right ml-4 flex-shrink-0">
                                        <p class="font-medium {{ $transaccion->tipo == 'Ingreso' ? 'text-emerald-600' : 'text-red-600' }}">{{ $transaccion->tipo == 'Ingreso' ? '+' : '-' }} ${{ number_format($transaccion->monto, 0, ',', '.') }}</p>
                                        <div class="mt-2 flex items-center justify-end gap-2">
                                            <a href="{{ route('transacciones.edit', $transaccion->id) }}" class="text-amber-600 hover:text-amber-700 text-sm">Editar</a>
                                            <form action="{{ route('transacciones.destroy', $transaccion->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-700 text-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar esta transacción?')">Eliminar</button>
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