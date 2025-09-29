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
                    <form action="{{ route('documentos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- ANTES: Código HTML manual con clases codificadas --}}
                        {{-- AHORA: Usando componentes de Blade reutilizables y estandarizados --}}
                        <div class="space-y-6">
                            <div>
                                <x-input-label for="nombre_documento" :value="__('Nombre del Documento')" />
                                <x-text-input id="nombre_documento" name="nombre_documento" type="text" class="mt-1 block w-full" :value="old('nombre_documento')" required autofocus />
                                <x-input-error :messages="$errors->get('nombre_documento')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="fecha_documento" :value="__('Fecha del Documento (de emisión, actualización, etc.)')" />
                                <x-text-input id="fecha_documento" name="fecha_documento" type="date" class="mt-1 block w-full" :value="old('fecha_documento')" required />
                                <x-input-error :messages="$errors->get('fecha_documento')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="descripcion" :value="__('Descripción (Opcional)')" />
                                <x-textarea-input id="descripcion" name="descripcion" rows="3" class="mt-1 block w-full">{{ old('descripcion') }}</x-textarea-input>
                                <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="archivo" :value="__('Seleccionar Archivo')" />
                                <x-file-input id="archivo" name="archivo" class="mt-1 block w-full" required />
                                <x-input-error :messages="$errors->get('archivo')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('documentos.index') }}">
                                <x-secondary-button type="button">
                                    {{ __('Cancelar') }}
                                </x-secondary-button>
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Guardar Documento') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>