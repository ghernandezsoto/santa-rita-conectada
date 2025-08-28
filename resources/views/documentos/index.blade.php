<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Archivo Digital') }}
            </h2>
            <a href="{{ route('documentos.create') }}" class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600">
                Subir Nuevo Documento
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">¡Éxito!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">¡Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    {{-- VISTA DE ESCRITORIO (TABLA) --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full bg-white table-fixed">
                            <thead class="bg-emerald-800 text-white">
                                <tr>
                                    <th class="w-5/12 py-3 px-4 font-semibold text-sm text-left">Nombre del Documento</th>
                                    <th class="w-2/12 py-3 px-4 font-semibold text-sm text-left">Fecha</th>
                                    <th class="w-3/12 py-3 px-4 font-semibold text-sm text-left">Subido por</th>
                                    <th class="w-2/12 py-3 px-4 font-semibold text-sm text-left">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($documentos as $documento)
                                    <tr class="border-b hover:bg-slate-50">
                                        <td class="py-3 px-4 font-medium truncate" title="{{ $documento->nombre_documento }}">{{ $documento->nombre_documento }}</td>
                                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($documento->fecha_documento)->format('d/m/Y') }}</td>
                                        <td class="py-3 px-4 truncate" title="{{ $documento->user->name ?? '' }}">{{ $documento->user->name ?? 'Usuario no encontrado' }}</td>
                                        <td class="py-3 px-4">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('documentos.show', $documento) }}" class="text-emerald-600 hover:text-emerald-900 font-medium">Descargar</a>
                                                <span class="text-gray-300">|</span>
                                                <form action="{{ route('documentos.destroy', $documento) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-700 font-medium" onclick="return confirm('¿Estás seguro de que quieres eliminar este documento?')">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-6 text-center text-gray-500">
                                            No hay documentos en el archivo digital.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- VISTA MÓVIL (TARJETAS) --}}
                    <div class="md:hidden space-y-4">
                        @forelse ($documentos as $documento)
                            <div class="bg-white p-4 rounded-lg shadow border border-gray-200 space-y-3">
                                <h3 class="font-bold text-emerald-800 truncate" title="{{ $documento->nombre_documento }}">{{ $documento->nombre_documento }}</h3>
                                <div class="text-sm text-gray-600">
                                    <p><span class="font-semibold">Fecha:</span> {{ \Carbon\Carbon::parse($documento->fecha_documento)->format('d/m/Y') }}</p>
                                    <p><span class="font-semibold">Subido por:</span> {{ $documento->user->name ?? 'N/A' }}</p>
                                </div>
                                <div class="mt-4 flex items-center gap-4 border-t pt-3">
                                    <a href="{{ route('documentos.show', $documento) }}" class="text-emerald-600 hover:text-emerald-900 font-medium text-sm">Descargar</a>
                                    <form action="{{ route('documentos.destroy', $documento) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 font-medium text-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar este documento?')">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="py-6 text-center text-gray-500">No hay documentos en el archivo digital.</div>
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