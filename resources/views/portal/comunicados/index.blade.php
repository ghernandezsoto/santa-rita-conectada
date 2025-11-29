<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Comunicados') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-base-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">

                    {{-- Encabezado de sección con icono --}}
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                        <div class="bg-orange-100 p-2 rounded-lg text-orange-600">
                            {{-- Icono de Megáfono --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Tablón de Anuncios</h3>
                            <p class="text-sm text-gray-500">Noticias y avisos oficiales de la directiva.</p>
                        </div>
                    </div>

                    {{-- LISTA UNIFICADA (Escritorio y Móvil) --}}
                    {{-- Usamos un diseño de lista que se ve bien en ambos, reemplazando la tabla rígida --}}
                    <ul class="space-y-3">
                        @forelse ($comunicados as $comunicado)
                            <li class="group">
                                <a href="{{ route('portal.comunicados.show', $comunicado) }}" class="block bg-white p-4 rounded-xl border border-gray-100 shadow-sm hover:shadow-md hover:border-orange-300 hover:bg-orange-50 transition-all duration-200">
                                    <div class="flex justify-between items-start md:items-center">
                                        <div class="flex items-start gap-4">
                                            {{-- Icono pequeño decorativo --}}
                                            <div class="hidden md:block mt-1 text-gray-300 group-hover:text-orange-500 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                                </svg>
                                            </div>

                                            <div class="flex-1">
                                                <h4 class="text-lg font-bold text-gray-800 group-hover:text-primary-800 transition-colors mb-1">
                                                    {{ $comunicado->titulo }}
                                                </h4>
                                                
                                                <div class="flex flex-col sm:flex-row sm:items-center gap-y-1 gap-x-4 text-sm text-gray-500">
                                                    <span class="flex items-center gap-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                        {{ \Carbon\Carbon::parse($comunicado->fecha_envio)->format('d/m/Y') }}
                                                    </span>
                                                    <span class="hidden sm:inline text-gray-300">|</span>
                                                    <span class="flex items-center gap-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                                        Por: {{ $comunicado->user->name }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Flecha de acción --}}
                                        <div class="ml-4 text-gray-300 group-hover:text-orange-600 transform group-hover:translate-x-1 transition duration-200 self-center">
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                                    </svg>
                                    <p>No hay comunicados publicados por el momento.</p>
                                </div>
                            </li>
                        @endforelse
                    </ul>

                    <div class="mt-6">
                        {{ $comunicados->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>