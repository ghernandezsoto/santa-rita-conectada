<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Subir Nuevo Documento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{-- IMPORTANTE: enctype es necesario para la subida de archivos --}}
                    <form action="{{ route('documentos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="nombre_documento" class="block text-sm font-medium text-gray-700">Nombre del Documento</label>
                            <input type="text" name="nombre_documento" id="nombre_documento" class="mt-1 block w-full rounded-md border-gray-300" value="{{ old('nombre_documento') }}" required>
                            @error('nombre_documento')<p class="text-sm text-red-600 mt-2">{{ $message }}</p>@enderror
                        </div>

                        <div class="mb-4">
                            <label for="fecha_documento" class="block text-sm font-medium text-gray-700">Fecha del Documento (de emisión, actualización, etc.)</label>
                            <input type="date" name="fecha_documento" id="fecha_documento" class="mt-1 block w-full rounded-md border-gray-300" value="{{ old('fecha_documento') }}" required>
                            @error('fecha_documento')<p class="text-sm text-red-600 mt-2">{{ $message }}</p>@enderror
                        </div>

                        <div class="mb-4">
                            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción (Opcional)</label>
                            <textarea name="descripcion" id="descripcion" rows="3" class="mt-1 block w-full rounded-md border-gray-300">{{ old('descripcion') }}</textarea>
                            @error('descripcion')<p class="text-sm text-red-600 mt-2">{{ $message }}</p>@enderror
                        </div>

                        <div class="mb-4">
                            <label for="archivo" class="block text-sm font-medium text-gray-700">Seleccionar Archivo</label>
                            <input type="file" name="archivo" id="archivo" class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" required>
                            @error('archivo')<p class="text-sm text-red-600 mt-2">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('documentos.index') }}" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 mr-4">
                                Cancelar
                            </a>
                            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                                Guardar Documento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>