<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Mensaje de Bienvenida General --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    ¡Bienvenido de nuevo, {{ Auth::user()->name }}!
                </div>
            </div>

            {{-- WIDGETS SOLO PARA ROLES ADMINISTRATIVOS --}}
            @role('Presidente|Secretario|Tesorero')
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @role('Presidente|Secretario')
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-semibold text-gray-500">Total de Socios</h3>
                        <p class="text-3xl font-bold mt-2 text-gray-800">{{ $totalSocios }}</p>
                    </div>
                    @endrole

                    @role('Presidente|Tesorero')
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-semibold text-gray-500">Balance de Tesorería</h3>
                        <p class="text-3xl font-bold mt-2 {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            ${{ number_format($balance, 0, ',', '.') }}
                        </p>
                    </div>
                    @endrole

                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-semibold text-gray-500">Próximos Eventos</h3>
                        <p class="text-3xl font-bold mt-2 text-gray-800">0</p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-semibold text-gray-500">Comunicados Recientes</h3>
                        <p class="text-3xl font-bold mt-2 text-gray-800">0</p>
                    </div>
                </div>
            @endrole

            {{-- SECCIÓN DE ACTIVIDAD RECIENTE (SOLO PARA ROLES ADMIN) --}}
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

            {{-- MENSAJE PARA SOCIOS REGULARES --}}
            @role('Socio')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold">¡Bienvenido a la comunidad!</h3>
                        <p class="mt-2 text-gray-600">Desde aquí podrás acceder a las últimas noticias y a la información relevante de la Junta de Vecinos. Utiliza el menú de la izquierda para navegar.</p>
                    </div>
                </div>
            @endrole

        </div>
    </div>
</x-app-layout>