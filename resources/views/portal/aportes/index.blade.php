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
                            {{-- NUEVA COLUMNA: COMPROBANTE --}}
                            <th class="py-2 px-4 text-center text-sm font-semibold text-gray-600">Comprobante</th>
                            <th class="py-2 px-4 text-right text-sm font-semibold text-gray-600">Monto</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($transacciones as $transaccion)
                            <tr>
                                {{-- Fecha formateada --}}
                                <td class="py-3 px-4 text-sm whitespace-nowrap">{{ $transaccion->fecha->format('d/m/Y') }}</td>
                                
                                {{-- Descripción --}}
                                <td class="py-3 px-4 text-sm">{{ $transaccion->descripcion }}</td>
                                
                                {{-- Badge de Tipo --}}
                                <td class="py-3 px-4 text-sm">
                                    <span class="py-1 px-2 rounded-full text-xs font-semibold {{ $transaccion->tipo === 'Ingreso' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $transaccion->tipo }}
                                    </span>
                                </td>

                                {{-- LOGICA DEL BOTÓN DE COMPROBANTE --}}
                                <td class="py-3 px-4 text-center text-sm">
                                    @if($transaccion->comprobante_path)
                                        {{-- Enlace directo a la ruta que ya creamos --}}
                                        <a href="{{ route('portal.comprobantes.descargar', $transaccion) }}" 
                                        target="_blank"
                                        class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Ver
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-xs italic">No adjunto</span>
                                    @endif
                                </td>

                                {{-- Monto --}}
                                <td class="py-3 px-4 text-right text-sm font-mono font-bold text-gray-700">
                                    ${{ number_format($transaccion->monto, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-gray-500">No tienes transacciones registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                    </div>

                    
                    @if ($transacciones instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="mt-4">
                            {{ $transacciones->links() }}
                        </div>
                    @endif
                    

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script type="module">
            import { Chart } from 'https://cdn.jsdelivr.net/npm/chart.js@4.4.3/auto/+esm';

            (async function() {
                const chartElement = document.getElementById('personalContributionsChart');
                if (!chartElement) return; 

                try {
                    // Llamamos al nuevo endpoint para los datos personales del socio
                    const response = await axios.get('/api/charts/personal-finances');
                    const data = response.data;

                    // Si no hay datos (ej. socio nuevo sin aportes), no dibujamos el gráfico

                    if (!data || !data.labels || data.labels.length === 0 || !data.datasets || data.datasets.length === 0 || data.datasets[0].data.length === 0) {
                        chartElement.parentElement.innerHTML = '<p class="text-center text-gray-500 py-4">No hay datos suficientes para mostrar el gráfico.</p>';
                        return;
                    }


                    new Chart(chartElement.getContext('2d'), { 
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

                    if (error.response && error.response.status === 404) {

                        chartElement.parentElement.innerHTML = '<p class="text-center text-gray-500 py-4">No se encontró un perfil de socio asociado para mostrar el gráfico.</p>';
                    } else {

                        console.error('Error al cargar los datos del gráfico personal:', error);

                        chartElement.parentElement.innerHTML = '<p class="text-center text-red-500 py-4">Error al cargar el gráfico.</p>';
                    }

                }
            })();
        </script>
        @endpush

</x-app-layout>