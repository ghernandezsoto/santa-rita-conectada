<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Aportes') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-base-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- Tarjeta de Saldo --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 {{ $balancePersonal >= 0 ? 'border-green-500' : 'border-red-500' }} border-t border-r border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-xs sm:text-sm font-bold text-gray-500 uppercase tracking-wider">Tu Saldo Actual</h3>
                        <p class="text-3xl sm:text-4xl font-bold mt-2 {{ $balancePersonal >= 0 ? 'text-green-600' : 'text-red-600' }} tabular-nums break-all">
                            ${{ number_format($balancePersonal, 0, ',', '.') }}
                        </p>
                        <p class="text-xs sm:text-sm text-gray-400 mt-1">Balance total de cuotas y aportes.</p>
                    </div>
                    {{-- Icono decorativo --}}
                    <div class="{{ $balancePersonal >= 0 ? 'text-green-100' : 'text-red-100' }} flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 sm:h-16 sm:w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Gráfico --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-4 sm:p-6 text-gray-900">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                        </svg>
                        Resumen de Aportes
                    </h3>
                    <div class="relative h-64 w-full">
                        <canvas id="personalContributionsChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Historial de Movimientos --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-4 sm:p-6 text-gray-900">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Historial Completo</h3>
                    
                    {{-- VISTA DE ESCRITORIO (TABLA) --}}
                    <div class="hidden md:block overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full">
                            <thead class="bg-primary-800 text-white uppercase text-xs leading-normal">
                                <tr>
                                    <th class="py-3 px-4 text-left font-semibold w-[15%]">Fecha</th>
                                    <th class="py-3 px-4 text-left font-semibold w-[40%]">Descripción</th>
                                    <th class="py-3 px-4 text-center font-semibold w-[15%]">Tipo</th>
                                    <th class="py-3 px-4 text-center font-semibold w-[15%]">Comprobante</th>
                                    <th class="py-3 px-4 text-right font-semibold w-[15%]">Monto</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 text-gray-600 text-sm font-light">
                                @forelse ($transacciones as $transaccion)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="py-3 px-4 whitespace-nowrap font-medium text-gray-800">
                                            {{ $transaccion->fecha->format('d/m/Y') }}
                                        </td>
                                        <td class="py-3 px-4 truncate max-w-xs" title="{{ $transaccion->descripcion }}">
                                            {{ $transaccion->descripcion }}
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <span class="py-1 px-3 rounded-full text-xs font-bold {{ $transaccion->tipo === 'Ingreso' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $transaccion->tipo }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            @if($transaccion->comprobante_path)
                                                <a href="{{ route('portal.comprobantes.descargar', $transaccion) }}" target="_blank" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 font-medium transition group">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                                    <span class="hidden lg:inline">Ver</span>
                                                </a>
                                            @else
                                                <span class="text-gray-300 italic text-xs">N/A</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-right font-mono font-bold text-base {{ $transaccion->tipo === 'Ingreso' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $transaccion->tipo === 'Ingreso' ? '+' : '-' }} ${{ number_format($transaccion->monto, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-gray-500">No tienes transacciones registradas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- VISTA MÓVIL (TARJETAS) --}}
                    <div class="md:hidden space-y-4">
                        @forelse ($transacciones as $transaccion)
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="text-sm font-bold text-gray-800">
                                        {{ $transaccion->fecha->format('d/m/Y') }}
                                    </div>
                                    <span class="text-base font-bold font-mono {{ $transaccion->tipo === 'Ingreso' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transaccion->tipo === 'Ingreso' ? '+' : '-' }} ${{ number_format($transaccion->monto, 0, ',', '.') }}
                                    </span>
                                </div>
                                
                                <p class="text-sm text-gray-600 mb-3 break-words">
                                    {{ $transaccion->descripcion }}
                                </p>

                                <div class="flex justify-between items-center border-t border-gray-100 pt-3">
                                    <span class="py-1 px-2 rounded-full text-xs font-bold {{ $transaccion->tipo === 'Ingreso' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $transaccion->tipo }}
                                    </span>

                                    @if($transaccion->comprobante_path)
                                        <a href="{{ route('portal.comprobantes.descargar', $transaccion) }}" target="_blank" class="text-blue-600 text-sm font-medium flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                                            Ver Comprobante
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Sin comprobante</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-gray-500 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                                No tienes transacciones registradas.
                            </div>
                        @endforelse
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
                    const response = await axios.get('/api/charts/personal-finances');
                    const data = response.data;

                    if (!data || !data.labels || data.labels.length === 0 || !data.datasets || data.datasets.length === 0 || data.datasets[0].data.length === 0) {
                        chartElement.parentElement.innerHTML = '<div class="text-center text-gray-500 py-10 flex flex-col items-center"><svg class="h-10 w-10 mb-2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"/></svg><p>No hay datos suficientes para mostrar el gráfico.</p></div>';
                        return;
                    }

                    new Chart(chartElement.getContext('2d'), { 
                        type: 'line', 
                        data: data,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { color: '#f3f4f6' }
                                },
                                x: {
                                    grid: { display: false }
                                }
                            },
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                }
                            },
                            interaction: {
                                mode: 'nearest',
                                axis: 'x',
                                intersect: false
                            }
                        }
                    });
                } catch (error) {
                    if (error.response && error.response.status === 404) {
                        chartElement.parentElement.innerHTML = '<p class="text-center text-gray-500 py-4">No se encontró un perfil de socio asociado.</p>';
                    } else {
                        console.error('Error gráfico:', error);
                        chartElement.parentElement.innerHTML = '<p class="text-center text-red-500 py-4">Error al cargar el gráfico.</p>';
                    }
                }
            })();
        </script>
    @endpush

</x-app-layout>