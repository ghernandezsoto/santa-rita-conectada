<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Archivo Digital') }}
            </h2>

            {{-- Botón con color accent-500 --}}
            <a href="{{ route('documentos.create') }}" class="px-4 py-2 bg-accent-500 text-white rounded-lg hover:bg-accent-600 flex items-center gap-2 transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                <span class="hidden sm:inline">Subir Nuevo Documento</span>
                <span class="sm:hidden">Subir</span>
            </a>
        </div>
    </x-slot>

    {{-- Fondo base-100 --}}
    <div class="py-12 bg-base-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r" role="alert">
                            <strong class="font-bold">¡Éxito!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r" role="alert">
                            <strong class="font-bold">¡Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    {{-- VISTA DE ESCRITORIO (TABLA) --}}
                    {{-- CORRECCIÓN: Separamos el contenedor hidden del overflow para evitar scroll lateral fantasma --}}
                    <div class="hidden md:block">
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full bg-white">
                                <thead class="bg-primary-800 text-white uppercase text-xs leading-normal">
                                    <tr>
                                        <th class="py-3 px-4 text-left font-semibold w-1/3">Nombre del Documento</th>
                                        <th class="py-3 px-4 text-center font-semibold w-1/6">Fecha</th>
                                        <th class="py-3 px-4 text-left font-semibold w-1/3">Subido por</th>
                                        <th class="py-3 px-4 text-center font-semibold w-1/6">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 text-sm font-light">
                                    @forelse ($documentos as $documento)
                                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                            <td class="py-3 px-4 font-medium text-gray-800">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    <span class="truncate max-w-xs" title="{{ $documento->nombre_documento }}">{{ $documento->nombre_documento }}</span>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                                <span class="bg-blue-50 text-blue-700 py-1 px-3 rounded-full text-xs font-semibold">
                                                    {{ \Carbon\Carbon::parse($documento->fecha_documento)->format('d/m/Y') }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-4 truncate max-w-[150px]" title="{{ $documento->user->name ?? 'Usuario no encontrado' }}">
                                                <span class="text-gray-500 text-xs">{{ $documento->user->name ?? 'Usuario no encontrado' }}</span>
                                            </td>
                                            <td class="py-3 px-4 text-center">
                                                <div class="flex item-center justify-center gap-3">
                                                    <a href="{{ route('documentos.show', $documento) }}" class="text-blue-500 hover:text-blue-700 hover:scale-110 transform transition" title="Descargar">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                                    </a>
                                                    <form action="{{ route('documentos.destroy', $documento) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este documento? Esta acción no se puede deshacer.');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="text-red-500 hover:text-red-700 hover:scale-110 transform transition" title="Eliminar">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="py-8 text-center text-gray-500">No hay documentos.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- VISTA MÓVIL (TARJETAS) --}}
                    <div class="md:hidden space-y-4">
                        @forelse ($documentos as $documento)
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 space-y-3">
                                <div class="flex items-start justify-between mb-2 gap-2">
                                    <div class="flex items-center gap-2 overflow-hidden min-w-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <h3 class="font-bold text-primary-800 truncate text-sm" title="{{ $documento->nombre_documento }}">{{ $documento->nombre_documento }}</h3>
                                    </div>
                                    <span class="text-xs text-blue-700 bg-blue-50 px-2 py-1 rounded-full flex-shrink-0 font-semibold whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($documento->fecha_documento)->format('d/m/Y') }}
                                    </span>
                                </div>
                                
                                <div class="text-xs text-gray-500 mb-3 truncate">
                                    Subido por: {{ $documento->user->name ?? 'N/A' }}
                                </div>

                                <div class="flex justify-end items-center gap-4 border-t pt-3">
                                    <a href="{{ route('documentos.show', $documento) }}" class="text-blue-600 font-medium text-sm flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                        Descargar
                                    </a>
                                    
                                    <form action="{{ route('documentos.destroy', $documento) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro?')">
                                        @csrf @method('DELETE')
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

                    <div class="mt-4">{{ $documentos->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
