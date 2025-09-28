<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Subir Nueva Acta') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- El atributo enctype es CRUCIAL para la subida de archivos --}}
                    <form method="POST" action="{{ route('actas.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <x-input-label for="titulo" :value="__('Título del Acta')" />
                                <x-text-input id="titulo" class="block mt-1 w-full" type="text" name="titulo" :value="old('titulo')" required autofocus />
                                <x-input-error :messages="$errors->get('titulo')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="fecha" :value="__('Fecha del Acta')" />
                                <x-text-input id="fecha" class="block mt-1 w-full" type="date" name="fecha" :value="old('fecha')" required />
                                <x-input-error :messages="$errors->get('fecha')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="contenido" :value="__('Contenido o Resumen del Acta')" />
                                {{-- ANTES: <textarea> con clases codificadas --}}
                                <x-textarea-input id="contenido" name="contenido" class="block mt-1 w-full" rows="5">{{ old('contenido') }}</x-textarea-input>
                                <x-input-error :messages="$errors->get('contenido')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="archivo" :value="__('Archivo PDF del Acta')" />
                                {{-- ANTES: <input type="file"> con clases codificadas --}}
                                <x-file-input id="archivo" name="archivo" class="block mt-1 w-full" required />
                                <x-input-error :messages="$errors->get('archivo')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('actas.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Cancelar</a>
                            <x-primary-button>
                                {{ __('Guardar Acta') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>