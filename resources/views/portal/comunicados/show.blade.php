<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle del Comunicado') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-base-100">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">
                    
                    {{-- Título del Comunicado --}}
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ $comunicado->titulo }}</h1>

                    {{-- Metadatos del Comunicado --}}
                    <div class="text-sm text-gray-500 mt-2 border-b pb-4 mb-6">
                        <span>Publicado por: {{ $comunicado->user->name }}</span>
                        <span class="mx-2">|</span>
                        <span>Fecha: {{ \Carbon\Carbon::parse($comunicado->fecha_envio)->format('d/m/Y') }}</span>
                    </div>

                    {{-- Contenido del Comunicado --}}
                    <div class="mt-6 text-gray-700 text-base leading-relaxed whitespace-pre-line">
                        {{ $comunicado->contenido }}
                    </div>
                    
                    {{-- Botón para volver al listado --}}
                    <div class="mt-8 pt-6 border-t flex justify-end">
                        <a href="{{ route('portal.comunicados.index') }}">
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