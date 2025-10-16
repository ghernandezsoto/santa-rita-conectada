<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel Principal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Bienvenida para la Directiva --}}
            @unlessrole('Socio')
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    ¡Bienvenido de nuevo, {{ Auth::user()->name }}!
                </div>
            </div>
            @endunlessrole

            {{-- ====================================================== --}}
            {{-- SECCIÓN PARA LA DIRECTIVA --}}
            {{-- ====================================================== --}}
            @role('Presidente|Secretario|Tesorero')
                {{-- WIDGETS DE RESUMEN --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @role('Presidente|Secretario')
                    <a href="{{ route('socios.index') }}" class="block transform hover:scale-105 transition-transform duration-200">
                        <div class="bg-white p-6 rounded-lg shadow-md h-full">
                            <h3 class="text-lg font-semibold text-gray-500">Total de Socios</h3>
                            <p class="text-3xl font-bold mt-2 text-gray-800">{{ $totalSocios }}</p>
                        </div>
                    </a>
                    @endrole

                    @role('Presidente|Tesorero')
                    <a href="{{ route('transacciones.index') }}" class="block transform hover:scale-105 transition-transform duration-200">
                        <div class="bg-white p-6 rounded-lg shadow-md h-full">
                            <h3 class="text-lg font-semibold text-gray-500">Balance de Tesorería</h3>
                            <p class="text-3xl font-bold mt-2 {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                ${{ number_format($balance, 0, ',', '.') }}
                            </p>
                        </div>
                    </a>
                    @endrole

                    <a href="{{ route('eventos.index') }}" class="block transform hover:scale-105 transition-transform duration-200">
                        <div class="bg-white p-6 rounded-lg shadow-md h-full">
                            <h3 class="text-lg font-semibold text-gray-500">Próximos Eventos</h3>
                            <p class="text-3xl font-bold mt-2 text-gray-800">{{ $proximosEventos }}</p>
                        </div>
                    </a>

                    <a href="{{ route('comunicados.index') }}" class="block transform hover:scale-105 transition-transform duration-200">
                        <div class="bg-white p-6 rounded-lg shadow-md h-full">
                            <h3 class="text-lg font-semibold text-gray-500">Comunicados Recientes</h3>
                            <p class="text-3xl font-bold mt-2 text-gray-800">{{ $comunicadosRecientes }}</p>
                        </div>
                    </a>
                </div>

                @role('Presidente|Tesorero')
                <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Resumen Financiero (Últimos 6 Meses)</h3>
                        <div>
                            <canvas id="financeChart"></canvas>
                        </div>
                    </div>
                </div>
                @endrole

                @role('Presidente|Secretario')
                    <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-semibold mb-4">Últimos Socios Registrados</h3>
                            <ul class="divide-y divide-gray-200">
                                @forelse ($ultimosSocios as $socio)
                                    <li class="py-3 flex justify-between items-center">
                                        <span>{{ $socio->nombre }}</span>
                                        <span class="text-sm text-gray-500">{{ $socio->created_at->diffForHumans() }}</span>
                                    </li>
                                @empty
                                    <li class="py-3 text-center text-gray-500">Aún no hay socios registrados.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                @endrole
            @endrole

            {{-- ====================================================== --}}
            {{-- SECCIÓN PARA SOCIOS --}}
            {{-- ====================================================== --}}
            @role('Socio')
            <div class="space-y-6">
                {{-- Tarjeta de Bienvenida --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-2xl font-semibold text-gray-800">¡Bienvenido, {{ Auth::user()->name }}! 👋</h3>
                        <p class="mt-2 text-gray-600">Este es tu portal personal. Aquí encontrarás las últimas noticias y acceso a documentos importantes de nuestra comunidad.</p>
                    </div>
                </div>

                {{-- Resumen de Documentos y Actas --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-semibold mb-4">Últimos Documentos</h3>
                            <ul class="divide-y divide-gray-200">
                                @forelse ($ultimosDocumentos as $documento)
                                    <li class="py-3 flex justify-between items-center">
                                        <a href="{{ route('portal.documentos.show', $documento->id) }}" class="text-primary-600 hover:underline hover:text-primary-800">
                                            {{ $documento->nombre_documento }}
                                        </a>
                                        <span class="text-sm text-gray-500 flex-shrink-0 ml-4">{{ $documento->created_at->diffForHumans() }}</span>
                                    </li>
                                @empty
                                    <li class="py-3 text-center text-gray-500">No hay documentos disponibles.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-semibold mb-4">Últimas Actas</h3>
                            <ul class="divide-y divide-gray-200">
                                @forelse ($ultimasActas as $acta)
                                    <li class="py-3 flex justify-between items-center">
                                        <a href="{{ route('portal.actas.show', $acta->id) }}" class="text-primary-600 hover:underline hover:text-primary-800">
                                            {{ $acta->titulo }}
                                        </a>
                                        <span class="text-sm text-gray-500 flex-shrink-0 ml-4">{{ $acta->created_at->diffForHumans() }}</span>
                                    </li>
                                @empty
                                    <li class="py-3 text-center text-gray-500">No hay actas disponibles.</li>
                                @endforelse
                            </ul>
                        </div>
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
                        scales: { y: { beginAtZero: true } },
                        plugins: {
                            legend: { position: 'top' },
                            title: { display: true, text: 'Ingresos vs. Egresos' }
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