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
                    <h3 class="text-lg font-semibold mb-4">Resumen de Aportes (Últimos 12 Meses)</h3>
                    <div>
                        <canvas id="personalContributionsChart"></canvas>
                    </div>
                </div>
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

                    {{-- --- MODIFICATION: Added @if check --- --}}
                    @if ($transacciones instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="mt-4">
                            {{ $transacciones->links() }}
                        </div>
                    @endif
                    {{-- --- END OF MODIFICATION --- --}}

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script type="module">
        import { Chart } from 'https://cdn.jsdelivr.net/npm/chart.js@4.4.3/auto/+esm';

        (async function() {
            try {
                // Llamamos al nuevo endpoint para los datos personales del socio
                const response = await axios.get('/api/charts/personal-finances');
                const data = response.data;

                // Si no hay datos (ej. socio nuevo sin aportes), no dibujamos el gráfico
                if (data.labels.length === 0) return;

                const ctx = document.getElementById('personalContributionsChart');
                if (!ctx) return;

                new Chart(ctx, {
                    type: 'line', // Un gráfico de línea es ideal para mostrar tendencias en el tiempo
                    data: data,
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            legend: {
                                display: false // No es necesaria la leyenda para una sola serie de datos
                            },
                            title: {
                                display: true,
                                text: 'Tus aportes mensuales'
                            }
                        }
                    }
                });
            } catch (error) {
                console.error('Error al cargar los datos del gráfico personal:', error);
            }
        })();
    </script>
    @endpush

</x-app-layout>