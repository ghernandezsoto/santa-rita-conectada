<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Documentos') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-base-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">

                    {{-- Encabezado de sección con icono AZUL --}}
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                        <div class="bg-blue-100 p-2 rounded-lg text-blue-600">
                            {{-- Icono de Carpeta/Documento --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Archivo Digital</h3>
                            <p class="text-sm text-gray-500">Reglamentos, informes y otros documentos de interés.</p>
                        </div>
                    </div>

                    {{-- Lista de Tarjetas --}}
                    <ul class="space-y-3">
                        @forelse ($documentos as $documento)
                            <li class="group">
                                <a href="{{ route('portal.documentos.show', $documento->id) }}" class="block bg-white p-4 rounded-xl border border-gray-100 shadow-sm hover:shadow-md hover:border-blue-300 hover:bg-blue-50 transition-all duration-200">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-start gap-4">
                                            {{-- Icono pequeño --}}
                                            <div class="mt-1 text-gray-300 group-hover:text-blue-500 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>

                                            <div>
                                                <h4 class="text-lg font-bold text-gray-800 group-hover:text-primary-800 transition-colors">
                                                    {{ $documento->nombre_documento }}
                                                </h4>
                                                <span class="flex items-center gap-1 text-sm text-gray-500 mt-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                    {{ $documento->created_at->format('d/m/Y') }}
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Flecha de acción --}}
                                        <div class="ml-4 text-gray-300 group-hover:text-blue-600 transform group-hover:translate-x-1 transition duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <li class="py-12 text-center text-gray-500 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                                <div class="flex flex-col items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p>No hay documentos disponibles en este momento.</p>
                                </div>
                            </li>
                        @endforelse
                    </ul>

                    <div class="mt-6">
                        {{ $documentos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>