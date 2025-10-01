<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle del Comunicado') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-3xl font-bold mb-2">{{ $comunicado->titulo }}</h3>

                    <div class="text-sm text-gray-500 mb-6">
                        <span>Publicado por: {{ $comunicado->user->name }}</span>
                        <span class="mx-2">|</span>
                        <span>Fecha: {{ $comunicado->created_at->format('d/m/Y H:i') }}</span>
                    </div>

                    <div class="prose max-w-none">
                        {!! $comunicado->contenido !!}
                    </div>
                    <div class="mt-8 flex justify-end">
                        <a href="{{ route('comunicados.index') }}">
                            <x-secondary-button type="button">
                                {{ __('Volver a la Lista') }}
                            </x-secondary-button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>