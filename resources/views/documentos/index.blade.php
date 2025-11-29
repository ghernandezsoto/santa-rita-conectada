<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Archivo Digital') }}
            </h2>

            <a href="{{ route('documentos.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2 transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Subir Nuevo Documento
            </a>
        </div>
    </x-slot>


    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r" role="alert">
                            <p class="font-bold">¡Éxito!</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r" role="alert">
                            <p class="font-bold">¡Error!</p>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    {{-- VISTA DE ESCRITORIO (TABLA) --}}
                    <div class="hidden md:block overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                                <tr>
                                    <th class="py-3 px-4 text-left font-semibold">Nombre del Documento</th>
                                    <th class="py-3 px-4 text-center font-semibold">Fecha</th>
                                    <th class="py-3 px-4 text-left font-semibold">Subido por</th>
                                    <th class="py-3 px-4 text-center font-semibold">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm font-light">
                                @forelse ($documentos as $documento)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-gray-800">
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                                {{ $documento->nombre_documento }}
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <span class="bg-gray-100 text-gray-600 py-1 px-3 rounded-full text-xs">
                                                {{ \Carbon\Carbon::parse($documento->fecha_documento)->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="text-gray-500 text-xs">{{ $documento->user->name ?? 'Usuario no encontrado' }}</span>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <div class="flex item-center justify-center gap-3">
                                                {{-- Botón Descargar --}}
                                                <a href="{{ route('documentos.show', $documento) }}" class="text-blue-500 hover:text-blue-700 transform hover:scale-110 transition" title="Descargar Documento">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                    </svg>
                                                </a>
                                                
                                                {{-- Botón Eliminar --}}
                                                <form action="{{ route('documentos.destroy', $documento) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este documento? Esta acción no se puede deshacer.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 transform hover:scale-110 transition" title="Eliminar Documento">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-gray-500 bg-gray-50">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                                <p>No hay documentos en el archivo digital.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- VISTA MÓVIL (TARJETAS) --}}
                    <div class="md:hidden space-y-4">
                        @forelse ($documentos as $documento)
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex items-center gap-2 overflow-hidden">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        <h3 class="font-bold text-gray-800 truncate text-sm">{{ $documento->nombre_documento }}</h3>
                                    </div>
                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full flex-shrink-0">
                                        {{ \Carbon\Carbon::parse($documento->fecha_documento)->format('d/m/Y') }}
                                    </span>
                                </div>
                                
                                <div class="text-xs text-gray-500 mb-4 ml-7">
                                    Subido por: {{ $documento->user->name ?? 'N/A' }}
                                </div>

                                <div class="flex justify-end items-center gap-4 border-t pt-3">
                                    <a href="{{ route('documentos.show', $documento) }}" class="text-blue-600 font-medium text-sm flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                        Descargar
                                    </a>
                                    
                                    <form action="{{ route('documentos.destroy', $documento) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 font-medium text-sm flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-gray-500 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                                No hay documentos.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        {{ $documentos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>