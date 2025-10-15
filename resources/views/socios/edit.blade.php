<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Socio: ') . $socio->nombre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('socios.update', $socio->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <x-input-label for="rut_visible" :value="__('RUT')" />
                                <x-text-input id="rut_visible" class="block mt-1 w-full" type="text" :value="old('rut', $socio->rut)" required autofocus />
                                <input type="hidden" name="rut" id="rut" value="{{ old('rut', $socio->getRawOriginal('rut')) }}">
                                <x-input-error :messages="$errors->get('rut')" class="mt-2" />
                            </div>
                            
                            <div>
                                <x-input-label for="nombre" :value="__('Nombre Completo')" />
                                <x-text-input id="nombre" class="block mt-1 w-full" type="text" name="nombre" :value="old('nombre', $socio->nombre)" required />
                                <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="domicilio" :value="__('Domicilio')" />
                                <x-text-input id="domicilio" class="block mt-1 w-full" type="text" name="domicilio" :value="old('domicilio', $socio->domicilio)" required />
                                <x-input-error :messages="$errors->get('domicilio')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="fecha_ingreso" :value="__('Fecha de Ingreso')" />
                                <x-text-input id="fecha_ingreso" class="block mt-1 w-full" type="date" name="fecha_ingreso" :value="old('fecha_ingreso', $socio->fecha_ingreso)" required />
                                <x-input-error :messages="$errors->get('fecha_ingreso')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="edad" :value="__('Edad')" />
                                <x-text-input id="edad" class="block mt-1 w-full" type="number" name="edad" :value="old('edad', $socio->edad)" />
                                <x-input-error :messages="$errors->get('edad')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="estado_civil" :value="__('Estado Civil')" />
                                <x-select-input id="estado_civil" name="estado_civil" class="block mt-1 w-full">
                                    <option value="">Seleccione...</option>
                                    @foreach ($estadosCiviles as $estado)
                                        <option value="{{ $estado }}" {{ old('estado_civil', $socio->estado_civil) == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                                    @endforeach
                                </x-select-input>
                                <x-input-error :messages="$errors->get('estado_civil')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="profesion" :value="__('Profesión / Oficio')" />
                                <x-select-input id="profesion" name="profesion" class="block mt-1 w-full">
                                    <option value="">Seleccione...</option>
                                    @foreach ($profesiones as $profesion)
                                        <option value="{{ $profesion }}" {{ old('profesion', $socio->profesion) == $profesion ? 'selected' : '' }}>{{ $profesion }}</option>
                                    @endforeach
                                </x-select-input>
                                <x-input-error :messages="$errors->get('profesion')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="telefono" :value="__('Teléfono')" />
                                <x-text-input id="telefono" class="block mt-1 w-full" type="text" name="telefono" :value="old('telefono', $socio->telefono)" placeholder="+56 9 1234 5678" />
                                <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Correo Electrónico')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $socio->email)" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            
                            <div>
                                <x-input-label for="estado" :value="__('Estado del Socio')" />
                                <x-select-input id="estado" name="estado" class="block mt-1 w-full">
                                    <option value="Activo" {{ old('estado', $socio->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                                    <option value="Inactivo" {{ old('estado', $socio->estado) == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                </x-select-input>
                                <x-input-error :messages="$errors->get('estado')" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="observaciones" :value="__('Observaciones')" />
                                <x-textarea-input id="observaciones" name="observaciones" class="block mt-1 w-full" rows="3">{{ old('observaciones', $socio->observaciones) }}</x-textarea-input>
                                <x-input-error :messages="$errors->get('observaciones')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('socios.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Cancelar</a>
                            <x-primary-button>
                                {{ __('Actualizar Socio') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var rutVisibleInput = document.getElementById('rut_visible');
            var rutHiddenInput = document.getElementById('rut');

            if (rutVisibleInput && rutHiddenInput) {
                var cleaveRut = new Cleave(rutVisibleInput, {
                    onValueChanged: function (e) {
                        rutHiddenInput.value = e.target.rawValue;
                        
                        var body = e.target.rawValue.slice(0, -1);
                        if (body.length <= 7) {
                            
                            cleaveRut.setBlocks([1, 3, 3, 1]);
                        } else {
                            
                            cleaveRut.setBlocks([2, 3, 3, 1]);
                        }
                    }
                });

                if (rutHiddenInput.value) {
                    cleaveRut.setRawValue(rutHiddenInput.value);
                }
            }

            var phoneInput = document.getElementById('telefono');
            if (phoneInput) {
                new Cleave(phoneInput, {
                    delimiters: [' ', ' ', ' '],
                    blocks: [3, 1, 4, 4],
                    numericOnly: true
                });
            }
        });
    </script>
    @endpush
</x-app-layout>