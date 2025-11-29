<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle del Evento') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-base-100">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl border border-gray-200">
                
                {{-- Cabecera Hero (Morado) --}}
                <div class="bg-purple-50 px-6 py-8 border-b border-purple-100 flex items-start gap-5">
                    <div class="bg-white p-3 rounded-xl shadow-sm text-purple-600 hidden sm:block">
                        {{-- Icono Calendario/Estrella --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight">
                            {{ $evento->titulo }}
                        </h1>
                        <p class="text-purple-700 mt-2 font-medium">¡No te lo pierdas!</p>
                    </div>
                </div>

                <div class="p-6 md:p-8">
                    {{-- Bloque de Información Clave (Fecha y Lugar) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        <div class="bg-blue-50 rounded-lg p-4 flex items-start gap-3 border border-blue-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <p class="text-xs font-bold text-blue-500 uppercase tracking-wide">Fecha y Hora</p>
                                <p class="text-gray-900 font-semibold mt-1">
                                    {{ \Carbon\Carbon::parse($evento->fecha_evento)->translatedFormat('l, d \d\e F \d\e Y') }}
                                </p>
                                <p class="text-gray-600 text-sm">
                                    A las {{ \Carbon\Carbon::parse($evento->fecha_evento)->format('H:i') }} hrs
                                </p>
                            </div>
                        </div>

                        <div class="bg-green-50 rounded-lg p-4 flex items-start gap-3 border border-green-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <div>
                                <p class="text-xs font-bold text-green-500 uppercase tracking-wide">Lugar</p>
                                <p class="text-gray-900 font-semibold mt-1">
                                    {{ $evento->lugar ?? 'Por definir' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Descripción --}}
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 mb-3 border-b pb-2">Acerca del evento</h3>
                        <div class="text-gray-700 text-base leading-relaxed whitespace-pre-line">
                            {{ $evento->descripcion }}
                        </div>
                    </div>
                </div>
                
                {{-- Pie de Página (Navegación) --}}
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end">
                    <a href="{{ route('portal.eventos.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        {{ __('Volver a la Lista') }}
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>