<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel Principal') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-base-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alertas del Sistema --}}
            @if (session('status'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-r shadow-sm" role="alert">
                    <strong class="font-bold">¡Información!</strong>
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif

            {{-- ====================================================== --}}
            {{-- SECCIÓN PARA LA DIRECTIVA --}}
            {{-- ====================================================== --}}
            @unlessrole('Socio')
                {{-- Tarjeta de Bienvenida Directiva (Estilo Hero) --}}
                <div class="bg-gradient-to-r from-primary-900 to-primary-700 rounded-xl shadow-lg mb-8 overflow-hidden relative">
                    <div class="p-8 text-white relative z-10">
                        <h3 class="text-2xl font-bold">¡Hola de nuevo, {{ Auth::user()->name }}! 👋</h3>
                        <p class="mt-2 text-primary-100 opacity-90">Aquí tienes el resumen ejecutivo de la gestión comunitaria de hoy.</p>
                    </div>
                    {{-- Decoración de fondo --}}
                    <div class="absolute right-0 top-0 h-full w-1/2 bg-white opacity-5 transform skew-x-12"></div>
                </div>

                @role('Presidente|Secretario|Tesorero')
                    {{-- WIDGETS DE RESUMEN --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        
                        @role('Presidente|Secretario')
                        <a href="{{ route('socios.index') }}" class="block group">
                            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500 hover:shadow-md transition-all duration-200 relative overflow-hidden h-full">
                                <div class="relative z-10">
                                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Socios</p>
                                    <p class="text-3xl font-bold text-gray-800 mt-1 group-hover:text-blue-600 transition-colors">{{ $totalSocios }}</p>
                                </div>
                                <div class="absolute right-4 top-4 text-blue-100 group-hover:scale-110 transition-transform duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                        @endrole

                        @role('Presidente|Tesorero')
                        <a href="{{ route('transacciones.index') }}" class="block group">
                            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 {{ $balance >= 0 ? 'border-green-500' : 'border-red-500' }} hover:shadow-md transition-all duration-200 relative overflow-hidden h-full">
                                <div class="relative z-10">
                                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Tesorería</p>
                                    <p class="text-3xl font-bold mt-1 {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }} tabular-nums">
                                        ${{ number_format($balance, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="absolute right-4 top-4 {{ $balance >= 0 ? 'text-green-100' : 'text-red-100' }} group-hover:scale-110 transition-transform duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                        @endrole

                        <a href="{{ route('eventos.index') }}" class="block group">
                            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-accent-500 hover:shadow-md transition-all duration-200 relative overflow-hidden h-full">
                                <div class="relative z-10">
                                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Eventos</p>
                                    <p class="text-3xl font-bold text-gray-800 mt-1 group-hover:text-accent-600 transition-colors">{{ $proximosEventos }}</p>
                                    <span class="text-xs text-gray-400">Próximos</span>
                                </div>
                                <div class="absolute right-4 top-4 text-purple-100 group-hover:scale-110 transition-transform duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('comunicados.index') }}" class="block group">
                            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-yellow-500 hover:shadow-md transition-all duration-200 relative overflow-hidden h-full">
                                <div class="relative z-10">
                                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Comunicados</p>
                                    <p class="text-3xl font-bold text-gray-800 mt-1 group-hover:text-yellow-600 transition-colors">{{ $comunicadosRecientes }}</p>
                                    <span class="text-xs text-gray-400">Recientes</span>
                                </div>
                                <div class="absolute right-4 top-4 text-yellow-100 group-hover:scale-110 transition-transform duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                    </div>

                    {{-- GRÁFICO Y LISTAS --}}
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        @role('Presidente|Tesorero')
                        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200">
                            <div class="p-6 border-b border-gray-100">
                                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" />
                                    </svg>
                                    Resumen Financiero (Semestral)
                                </h3>
                            </div>
                            <div class="p-6">
                                <canvas id="financeChart" height="300"></canvas>
                            </div>
                        </div>
                        @endrole

                        @role('Presidente|Secretario')
                        <div class="lg:col-span-1 bg-white rounded-xl shadow-sm border border-gray-200">
                            <div class="p-6 border-b border-gray-100 bg-gray-50 rounded-t-xl">
                                <h3 class="font-bold text-gray-800">Últimos Socios</h3>
                            </div>
                            <div class="p-4">
                                <ul class="space-y-4">
                                    @forelse ($ultimosSocios as $socio)
                                        <li class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold text-sm">
                                                {{ substr($socio->nombre, 0, 2) }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">{{ $socio->nombre }}</p>
                                                <p class="text-xs text-gray-500">{{ $socio->created_at->diffForHumans() }}</p>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="py-4 text-center text-gray-500 text-sm">No hay registros recientes.</li>
                                    @endforelse
                                </ul>
                                <div class="mt-4 pt-4 border-t border-gray-100 text-center">
                                    <a href="{{ route('socios.index') }}" class="text-sm text-accent-600 hover:text-accent-800 font-medium">Ver todos los socios &rarr;</a>
                                </div>
                            </div>
                        </div>
                        @endrole
                    </div>
                @endrole
            @endunlessrole

            {{-- ====================================================== --}}
            {{-- SECCIÓN PARA SOCIOS --}}
            {{-- ====================================================== --}}
            @role('Socio')
            <div class="space-y-8">
                {{-- Hero de Socio --}}
                <div class="bg-gradient-to-r from-accent-600 to-primary-700 rounded-2xl shadow-lg p-8 text-white">
                    <h3 class="text-3xl font-bold">¡Bienvenido, {{ Auth::user()->name }}! 👋</h3>
                    <p class="mt-2 text-lg text-blue-100 opacity-90">Accede a la información más reciente de tu comunidad y revisa documentos importantes.</p>
                </div>

                {{-- Grid de Listas --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Documentos --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gray-50 p-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                Últimos Documentos
                            </h3>
                            <a href="{{ route('portal.documentos.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">Ver todo</a>
                        </div>
                        <ul class="divide-y divide-gray-100">
                            @forelse ($ultimosDocumentos as $documento)
                                <li class="p-4 hover:bg-gray-50 transition flex items-center justify-between group">
                                    <a href="{{ route('portal.documentos.show', $documento->id) }}" class="flex items-center gap-3 flex-1 min-w-0">
                                        <div class="bg-blue-50 p-2 rounded text-blue-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                        </div>
                                        <span class="text-gray-700 font-medium truncate group-hover:text-blue-700 transition">{{ $documento->nombre_documento }}</span>
                                    </a>
                                    <span class="text-xs text-gray-400 whitespace-nowrap ml-2">{{ $documento->created_at->diffForHumans() }}</span>
                                </li>
                            @empty
                                <li class="p-6 text-center text-gray-500 italic">No hay documentos recientes.</li>
                            @endforelse
                        </ul>
                    </div>

                    {{-- Actas --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gray-50 p-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                                Últimas Actas
                            </h3>
                            <a href="{{ route('portal.actas.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">Ver todo</a>
                        </div>
                        <ul class="divide-y divide-gray-100">
                            @forelse ($ultimasActas as $acta)
                                <li class="p-4 hover:bg-gray-50 transition flex items-center justify-between group">
                                    <a href="{{ route('portal.actas.show', $acta->id) }}" class="flex items-center gap-3 flex-1 min-w-0">
                                        <div class="bg-yellow-50 p-2 rounded text-yellow-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        </div>
                                        <span class="text-gray-700 font-medium truncate group-hover:text-yellow-700 transition">{{ $acta->titulo }}</span>
                                    </a>
                                    <span class="text-xs text-gray-400 whitespace-nowrap ml-2">{{ $acta->created_at->diffForHumans() }}</span>
                                </li>
                            @empty
                                <li class="p-6 text-center text-gray-500 italic">No hay actas recientes.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
            @endrole

        </div>
    </div>

    @push('scripts')
    @role('Presidente|Tesorero')
    <script type="module">
        import { Chart } from 'https://cdn.jsdelivr.net/npm/chart.js@4.4.3/auto/+esm';

        (async function() {
            try {
                const response = await axios.get('/api/charts/finances');
                const data = response.data;

                const ctx = document.getElementById('financeChart');
                if (!ctx) return;

                new Chart(ctx, {
                    type: 'bar',
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false, // Importante para que se adapte al contenedor
                        scales: { y: { beginAtZero: true } },
                        plugins: {
                            legend: { position: 'bottom' }, // Leyenda abajo para más espacio
                            title: { display: false } // Título ya está en el HTML
                        }
                    }
                });
            } catch (error) {
                console.error('Error al cargar los datos del gráfico:', error);
            }
        })();
    </script>
    @endrole
    @endpush

</x-app-layout>