<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Próximos Eventos') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-base-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">

                    {{-- Encabezado de sección con icono MORADO --}}
                    <div class="flex items-center gap-3 mb-8 pb-4 border-b border-gray-100">
                        <div class="bg-purple-100 p-2 rounded-lg text-purple-600">
                            {{-- Icono Calendario --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Agenda de Actividades</h3>
                            <p class="text-sm text-gray-500">Reuniones, asambleas y eventos comunitarios.</p>
                        </div>
                    </div>

                    {{-- GRID DE TARJETAS (Reemplaza a la tabla) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse ($eventos as $evento)
                            {{-- Tarjeta de Evento --}}
                            <a href="{{ route('portal.eventos.show', $evento) }}" class="group bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-lg hover:border-purple-300 transition-all duration-300 overflow-hidden flex flex-col h-full">
                                
                                <div class="p-5 flex-1">
                                    <div class="flex items-start gap-4">
                                        {{-- Badge de Fecha (Estilo Calendario) --}}
                                        <div class="bg-purple-50 rounded-lg p-2 text-center min-w-[60px] border border-purple-100 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                                            <span class="block text-xl font-bold leading-none">
                                                {{ \Carbon\Carbon::parse($evento->fecha_evento)->format('d') }}
                                            </span>
                                            <span class="block text-xs uppercase font-semibold mt-1">
                                                {{ \Carbon\Carbon::parse($evento->fecha_evento)->translatedFormat('M') }}
                                            </span>
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-lg font-bold text-gray-800 group-hover:text-purple-700 transition-colors line-clamp-2" title="{{ $evento->titulo }}">
                                                {{ $evento->titulo }}
                                            </h4>
                                            
                                            <div class="mt-3 space-y-1.5">
                                                {{-- Hora --}}
                                                <div class="flex items-center text-sm text-gray-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ \Carbon\Carbon::parse($evento->fecha_evento)->format('H:i') }} hrs
                                                </div>
                                                
                                                {{-- Lugar --}}
                                                <div class="flex items-center text-sm text-gray-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    <span class="truncate">{{ $evento->lugar ?? 'Por definir' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Pie de tarjeta "Ver Detalles" --}}
                                <div class="bg-gray-50 px-5 py-3 border-t border-gray-100 flex justify-between items-center group-hover:bg-purple-50 transition-colors duration-300">
                                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide group-hover:text-purple-700">Ver Detalles</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 group-hover:text-purple-600 transform group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </div>
                            </a>
                        @empty
                            <div class="col-span-1 md:col-span-2 lg:col-span-3 py-12 text-center text-gray-500 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                                <div class="flex flex-col items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p>No hay eventos programados próximamente.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-8">
                        {{ $eventos->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>