<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle del Evento') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-base-100">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">
                    
                    {{-- Título del Evento --}}
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ $evento->titulo }}</h1>

                    {{-- Metadatos del Evento (Fecha y Lugar) --}}
                    <div class="mt-4 border-y py-4 space-y-3">
                        <div class="flex items-center text-sm text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="font-semibold mr-1">Fecha y Hora:</span>
                            {{ \Carbon\Carbon::parse($evento->fecha_evento)->translatedFormat('l, d \d\e F \d\e Y, H:i') }} hrs
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="font-semibold mr-1">Lugar:</span>
                            {{ $evento->lugar ?? 'No especificado' }}
                        </div>
                    </div>

                    {{-- Contenido/Descripción del Evento --}}
                    <div class="mt-6 text-gray-700 text-base leading-relaxed whitespace-pre-line">
                        {{ $evento->descripcion }}
                    </div>
                    
                    {{-- Botón para volver al listado --}}
                    <div class="mt-8 pt-6 border-t flex justify-end">
                        <a href="{{ route('portal.eventos.index') }}">
                            <x-secondary-button type="button">
                                {{ __('Volver a la Lista') }}
                            </x-secondary-button>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>