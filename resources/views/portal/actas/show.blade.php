<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $acta->titulo }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                     <p class="text-sm text-gray-500 mb-4">Fecha del acta: {{ $acta->fecha->format('d/m/Y') }}</p>
                    <p class="mb-6">{{ $acta->descripcion ?: 'Esta acta no tiene una descripción adicional.' }}</p>
                     <a href="{{ route('actas.show', $acta->id) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                        Descargar Acta
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>