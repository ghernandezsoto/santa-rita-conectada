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
                            <!-- RUT -->
                            <div>
                                <x-input-label for="rut" :value="__('RUT')" />
                                <x-text-input id="rut" class="block mt-1 w-full" type="text" name="rut" :value="old('rut', $socio->rut)" required autofocus placeholder="12.345.678-9" />
                                <x-input-error :messages="$errors->get('rut')" class="mt-2" />
                            </div>
                            <!-- Nombre Completo -->
                            <div>
                                <x-input-label for="nombre" :value="__('Nombre Completo')" />
                                <x-text-input id="nombre" class="block mt-1 w-full" type="text" name="nombre" :value="old('nombre', $socio->nombre)" required />
                                <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                            </div>
                            <!-- Domicilio -->
                            <div class="md:col-span-2">
                                <x-input-label for="domicilio" :value="__('Domicilio')" />
                                <x-text-input id="domicilio" class="block mt-1 w-full" type="text" name="domicilio" :value="old('domicilio', $socio->domicilio)" required />
                                <x-input-error :messages="$errors->get('domicilio')" class="mt-2" />
                            </div>
                            <!-- Fecha de Ingreso -->
                            <div>
                                <x-input-label for="fecha_ingreso" :value="__('Fecha de Ingreso')" />
                                <x-text-input id="fecha_ingreso" class="block mt-1 w-full" type="date" name="fecha_ingreso" :value="old('fecha_ingreso', $socio->fecha_ingreso)" required />
                                <x-input-error :messages="$errors->get('fecha_ingreso')" class="mt-2" />
                            </div>
                            <!-- Edad -->
                            <div>
                                <x-input-label for="edad" :value="__('Edad')" />
                                <x-text-input id="edad" class="block mt-1 w-full" type="number" name="edad" :value="old('edad', $socio->edad)" />
                                <x-input-error :messages="$errors->get('edad')" class="mt-2" />
                            </div>
                            <!-- Estado Civil -->
                            <div>
                                <x-input-label for="estado_civil" :value="__('Estado Civil')" />
                                <select id="estado_civil" name="estado_civil" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Seleccione...</option>
                                    @foreach ($estadosCiviles as $estado)
                                        <option value="{{ $estado }}" {{ old('estado_civil', $socio->estado_civil) == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('estado_civil')" class="mt-2" />
                            </div>
                            <!-- Profesión -->
                            <div>
                                <x-input-label for="profesion" :value="__('Profesión / Oficio')" />
                                <select id="profesion" name="profesion" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Seleccione...</option>
                                    @foreach ($profesiones as $profesion)
                                        <option value="{{ $profesion }}" {{ old('profesion', $socio->profesion) == $profesion ? 'selected' : '' }}>{{ $profesion }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('profesion')" class="mt-2" />
                            </div>
                            <!-- Teléfono -->
                            <div>
                                <x-input-label for="telefono" :value="__('Teléfono')" />
                                <x-text-input id="telefono" class="block mt-1 w-full" type="text" name="telefono" :value="old('telefono', $socio->telefono)" placeholder="+56 9 1234 5678" />
                                <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
                            </div>
                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Correo Electrónico')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $socio->email)" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                             <!-- Estado del Socio -->
                             <div>
                                <x-input-label for="estado" :value="__('Estado del Socio')" />
                                <select id="estado" name="estado" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="Activo" {{ old('estado', $socio->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                                    <option value="Inactivo" {{ old('estado', $socio->estado) == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                                <x-input-error :messages="$errors->get('estado')" class="mt-2" />
                            </div>
                            <!-- Observaciones -->
                            <div class="md:col-span-2">
                                <x-input-label for="observaciones" :value="__('Observaciones')" />
                                <textarea id="observaciones" name="observaciones" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('observaciones', $socio->observaciones) }}</textarea>
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

    {{-- SCRIPT PARA EL FORMATEO AUTOMÁTICO --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var rutInput = document.getElementById('rut');
            if (rutInput) {
                new Cleave(rutInput, {
                    delimiters: ['.', '.', '-'],
                    blocks: [2, 3, 3, 1],
                    numericOnly: true,
                    onValueChanged: function (e) {
                        if (e.target.rawValue.slice(-1).toLowerCase() === 'k') {
                            e.target.setRawValue(e.target.rawValue.slice(0, -1) + 'K');
                        }
                    }
                });
            }

            var phoneInput = document.getElementById('telefono');
            if (phoneInput) {
                 new Cleave(phoneInput, {
                    delimiters: [' ', ' ', ' '],
                    blocks: [3, 4, 4],
                    numericOnly: true,
                    prefix: '+56'
                });
            }
        });
    </script>
</x-app-layout>