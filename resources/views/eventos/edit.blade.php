<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Evento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('eventos.update', $evento) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Importante para la actualización --}}

                        <div class="mb-4">
                            <label for="titulo" class="block text-sm font-medium text-gray-700">Título del Evento</label>
                            <input type="text" name="titulo" id="titulo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('titulo', $evento->titulo) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                            <textarea name="descripcion" id="descripcion" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('descripcion', $evento->descripcion) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="fecha_evento" class="block text-sm font-medium text-gray-700">Fecha y Hora del Evento</label>
                            {{-- Formateamos la fecha para que el input la reconozca --}}
                            <input type="datetime-local" name="fecha_evento" id="fecha_evento" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('fecha_evento', \Carbon\Carbon::parse($evento->fecha_evento)->format('Y-m-d\TH:i')) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="lugar" class="block text-sm font-medium text-gray-700">Lugar (Opcional)</label>
                            <input type="text" name="lugar" id="lugar" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('lugar', $evento->lugar) }}">
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('eventos.index') }}" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 mr-4">
                                Cancelar
                            </a>
                            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                                Actualizar Evento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>