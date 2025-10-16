<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Aportes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-500">Tu Saldo Actual</h3>
                <p class="text-3xl font-bold mt-2 {{ $balancePersonal >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    ${{ number_format($balancePersonal, 0, ',', '.') }}
                </p>
                <p class="text-sm text-gray-500 mt-1">Este es el balance de todas tus cuotas y aportes registrados.</p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Historial Completo de Movimientos</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-2 px-4 text-left text-sm font-semibold text-gray-600">Fecha</th>
                                    <th class="py-2 px-4 text-left text-sm font-semibold text-gray-600">Descripción</th>
                                    <th class="py-2 px-4 text-left text-sm font-semibold text-gray-600">Tipo</th>
                                    <th class="py-2 px-4 text-right text-sm font-semibold text-gray-600">Monto</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($transacciones as $transaccion)
                                    <tr>
                                        <td class="py-3 px-4 text-sm">{{ $transaccion->fecha->format('d/m/Y') }}</td>
                                        <td class="py-3 px-4 text-sm">{{ $transaccion->descripcion }}</td>
                                        <td class="py-3 px-4 text-sm">
                                            <span class="py-1 px-2 rounded-full text-xs font-semibold {{ $transaccion->tipo === 'Ingreso' ? 'bg-success-100 text-success-800' : 'bg-danger-100 text-danger-800' }}">
                                                {{ $transaccion->tipo }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-right text-sm font-mono">${{ number_format($transaccion->monto, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-6 text-center text-gray-500">No tienes transacciones registradas.</td>
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