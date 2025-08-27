<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Archivo Digital') }}
            </h2>
            {{-- El enlace ahora apunta a la ruta de creación --}}
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

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-emerald-800 text-white">
                                <tr>
                                    <th class="py-3 px-4 font-semibold text-sm text-left">Nombre del Documento</th>
                                    <th class="py-3 px-4 font-semibold text-sm text-left">Fecha del Documento</th>
                                    <th class="py-3 px-4 font-semibold text-sm text-left">Subido por</th>
                                    <th class="py-3 px-4 font-semibold text-sm text-left">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($documentos as $documento)
                                    <tr class="border-b hover:bg-slate-50">
                                        <td class="py-3 px-4 font-medium">{{ $documento->nombre_documento }}</td>
                                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($documento->fecha_documento)->format('d/m/Y') }}</td>
                                        <td class="py-3 px-4">{{ $documento->user->name ?? 'Usuario no encontrado' }}</td>
                                        <td class="py-3 px-4 flex items-center gap-2">
                                            {{-- Los enlaces los haremos funcionar más adelante --}}
                                            <a href="#" class="text-emerald-600 hover:text-emerald-900 font-medium">Descargar</a>
                                            <span class="text-gray-300">|</span>
                                            <form action="#" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-700 font-medium" onclick="return confirm('¿Estás seguro?')">
                                                    Eliminar
                                                </button>
                                            </form>
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

                        <div class="mt-4">
                            {{ $documentos->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>