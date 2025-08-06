<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Nueva Postulación a Subsidio') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('subsidios.store') }}">
                        @csrf
                        <div class="space-y-6">

                            <div>
                                <x-input-label for="socio_id" :value="__('Socio Postulante')" />
                                <select id="socio_id" name="socio_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Seleccione un socio</option>
                                    @foreach ($socios as $socio)
                                        <option value="{{ $socio->id }}" {{ old('socio_id') == $socio->id ? 'selected' : '' }}>
                                            {{ $socio->nombre }} (RUT: {{ $socio->rut }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('socio_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="nombre_subsidio" :value="__('Nombre del Subsidio o Proyecto')" />
                                <x-text-input id="nombre_subsidio" class="block mt-1 w-full" type="text" name="nombre_subsidio" :value="old('nombre_subsidio')" required />
                                <x-input-error :messages="$errors->get('nombre_subsidio')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="monto_solicitado" :value="__('Monto Solicitado ($)')" />
                                <x-text-input id="monto_solicitado" class="block mt-1 w-full" type="number" name="monto_solicitado" :value="old('monto_solicitado')" required step="0.01" />
                                <x-input-error :messages="$errors->get('monto_solicitado')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="descripcion" :value="__('Descripción del Proyecto o Necesidad')" />
                                <textarea id="descripcion" name="descripcion" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="5">{{ old('descripcion') }}</textarea>
                                <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('subsidios.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Cancelar</a>
                            <x-primary-button>
                                {{ __('Guardar Postulación') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>