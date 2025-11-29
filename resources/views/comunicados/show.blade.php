<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle del Comunicado') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-base-100">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl border border-gray-200">
                
                {{-- Cabecera de la Tarjeta (Hero - Tema Naranja para Comunicados) --}}
                <div class="bg-orange-50 px-6 py-8 border-b border-orange-100 flex items-start gap-5">
                    <div class="bg-white p-3 rounded-xl shadow-sm text-orange-600 hidden sm:block">
                        {{-- Icono Megáfono --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight">
                            {{ $comunicado->titulo }}
                        </h1>
                        
                        {{-- Metadatos --}}
                        <div class="flex flex-wrap items-center gap-x-6 gap-y-2 mt-3 text-sm text-gray-600">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>Publicado por: <span class="font-semibold">{{ $comunicado->user->name }}</span></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Fecha: {{ $comunicado->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Cuerpo --}}
                <div class="p-6 md:p-8">
                    <div class="mb-2">
                        <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">Contenido del Mensaje</h4>
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                            <div class="prose max-w-none text-gray-800 leading-relaxed whitespace-pre-line">
                                {!! $comunicado->contenido !!}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pie de Página (Acciones) --}}
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end">
                    <a href="{{ route('comunicados.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
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