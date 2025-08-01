<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Comunicado') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('comunicados.update', $comunicado->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="space-y-6">
                            <div>
                                <x-input-label for="titulo" :value="__('Título del Comunicado')" />
                                <x-text-input id="titulo" class="block mt-1 w-full" type="text" name="titulo" :value="old('titulo', $comunicado->titulo)" required autofocus />
                                <x-input-error :messages="$errors->get('titulo')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="contenido" :value="__('Contenido del Mensaje')" />
                                <textarea id="contenido" name="contenido" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="10">{{ old('contenido', $comunicado->contenido) }}</textarea>
                                <x-input-error :messages="$errors->get('contenido')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('comunicados.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Cancelar</a>
                            <x-primary-button>
                                {{ __('Actualizar Comunicado') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>