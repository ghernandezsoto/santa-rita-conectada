<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle del Documento') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-base-100">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl border border-gray-200">
                
                {{-- Cabecera de la Tarjeta (Hero) --}}
                <div class="bg-blue-50 px-6 py-8 border-b border-blue-100 flex items-start gap-5">
                    <div class="bg-white p-3 rounded-xl shadow-sm text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 leading-tight">
                            {{ $documento->nombre ?? $documento->nombre_documento }}
                        </h3>
                        <div class="flex items-center gap-2 mt-2 text-sm text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Publicado el {{ $documento->created_at->format('d \d\e F \d\e Y') }}
                        </div>
                    </div>
                </div>

                {{-- Cuerpo de la Tarjeta --}}
                <div class="p-6 sm:p-8">
                    <div class="mb-2">
                        <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">Descripción / Detalles</h4>
                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 text-gray-700 leading-relaxed">
                            {{ $documento->descripcion ?: 'No se ha proporcionado una descripción adicional para este documento.' }}
                        </div>
                    </div>
                </div>

                {{-- Pie de Página (Acciones) --}}
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                    {{-- Botón Volver --}}
                    <a href="{{ route('portal.documentos.index') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium flex items-center gap-1 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Volver al listado
                    </a>

                    {{-- Botón Descargar (Primary Action) --}}
                    <a href="{{ route('portal.documentos.descargar', $documento->id) }}" target="_blank" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Descargar Archivo
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>