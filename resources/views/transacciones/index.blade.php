<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tesorería') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700">Total Ingresos</h3>
                    <p class="text-3xl font-bold mt-2 text-green-600">${{ number_format($ingresos, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700">Total Egresos</h3>
                    <p class="text-3xl font-bold mt-2 text-red-600">${{ number_format($egresos, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700">Balance Actual</h3>
                    <p class="text-3xl font-bold mt-2 text-blue-600">${{ number_format($balance, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="mb-4 flex gap-4">
                <a href="#" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Registrar Ingreso
                </a>
                <a href="#" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Registrar Egreso
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Historial de Transacciones</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-800 text-white">
                                <tr>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Fecha</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Descripción</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Monto</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Registrado por</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($transacciones as $transaccion)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($transaccion->fecha)->format('d/m/Y') }}</td>
                                        <td class="py-3 px-4">{{ $transaccion->descripcion }}</td>
                                        <td class="py-3 px-4 font-medium {{ $transaccion->tipo == 'Ingreso' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $transaccion->tipo == 'Ingreso' ? '+' : '-' }} ${{ number_format($transaccion->monto, 0, ',', '.') }}
                                        </td>
                                        <td class="py-3 px-4">{{ $transaccion->user->name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-6 text-center text-gray-500">
                                            No hay transacciones registradas aún.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $transacciones->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>