<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Actas') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-base-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    
                    {{-- Encabezado de sección con icono --}}
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                        <div class="bg-yellow-100 p-2 rounded-lg text-yellow-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Listado de Actas</h3>
                            <p class="text-sm text-gray-500">Documentos oficiales de asambleas y reuniones.</p>
                        </div>
                    </div>

                    <ul class="space-y-2">
                        @forelse ($actas as $acta)
                            <li class="group">
                                <a href="{{ route('portal.actas.show', $acta->id) }}" class="block p-4 rounded-xl border border-gray-100 hover:border-yellow-300 hover:bg-yellow-50 transition duration-200">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-start gap-4">
                                            {{-- Icono de documento pequeño --}}
                                            <div class="mt-1 text-gray-300 group-hover:text-yellow-600 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            
                                            <div>
                                                <span class="block font-semibold text-gray-800 group-hover:text-primary-800 transition-colors text-base">
                                                    {{ $acta->titulo }}
                                                </span>
                                                <span class="flex items-center gap-1 text-sm text-gray-500 mt-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                    {{ $acta->fecha->format('d/m/Y') }}
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Flecha indicadora --}}
                                        <div class="text-gray-300 group-hover:text-yellow-600 transform group-hover:translate-x-1 transition duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p>No hay actas disponibles en este momento.</p>
                                </div>
                            </li>
                        @endforelse
                    </ul>

                    <div class="mt-6">
                        {{ $actas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>