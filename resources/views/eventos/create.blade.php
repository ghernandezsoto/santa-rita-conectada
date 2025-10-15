<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nuevo Evento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('eventos.store') }}" method="POST">
                        @csrf
                        
                        <div class="space-y-6">
                            <div>
                                <x-input-label for="titulo" :value="__('Título del Evento')" />
                                <x-text-input id="titulo" name="titulo" type="text" class="mt-1 block w-full" :value="old('titulo')" required autofocus />
                                <x-input-error :messages="$errors->get('titulo')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="descripcion" :value="__('Descripción')" />
                                <x-textarea-input id="descripcion" name="descripcion" class="mt-1 block w-full" rows="4" required>{{ old('descripcion') }}</x-textarea-input>
                                <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="fecha_evento" :value="__('Fecha y Hora del Evento')" />
                                <x-text-input id="fecha_evento" name="fecha_evento" type="datetime-local" class="mt-1 block w-full" :value="old('fecha_evento')" required />
                                <x-input-error :messages="$errors->get('fecha_evento')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="lugar" :value="__('Lugar (Opcional)')" />
                                <x-text-input id="lugar" name="lugar" type="text" class="mt-1 block w-full" :value="old('lugar')" />
                                <x-input-error :messages="$errors->get('lugar')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('eventos.index') }}">
                                <x-secondary-button type="button">
                                    {{ __('Cancelar') }}
                                </x-secondary-button>
                            </a>
                            
                            <x-primary-button class="ms-4">
                                {{ __('Guardar Evento') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>