<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Agregar Nuevo Socio') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('socios.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div>
                                <x-input-label for="rut_visible" :value="__('RUT')" />

                                <x-text-input id="rut_visible" class="block mt-1 w-full" type="text" :value="old('rut')" required autofocus placeholder="12.345.678-9" />
                                
                                <input type="hidden" name="rut" id="rut" value="{{ old('rut') }}">

                                <x-input-error :messages="$errors->get('rut')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="nombre" :value="__('Nombre Completo')" />
                                <x-text-input id="nombre" class="block mt-1 w-full" type="text" name="nombre" :value="old('nombre')" required />
                                <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="domicilio" :value="__('Domicilio')" />
                                <x-text-input id="domicilio" class="block mt-1 w-full" type="text" name="domicilio" :value="old('domicilio')" required />
                                <x-input-error :messages="$errors->get('domicilio')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="fecha_ingreso" :value="__('Fecha de Ingreso')" />
                                <x-text-input id="fecha_ingreso" class="block mt-1 w-full" type="date" name="fecha_ingreso" :value="old('fecha_ingreso', date('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('fecha_ingreso')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="edad" :value="__('Edad')" />
                                <x-text-input id="edad" class="block mt-1 w-full" type="number" name="edad" :value="old('edad')" />
                                <x-input-error :messages="$errors->get('edad')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="estado_civil" :value="__('Estado Civil')" />
                                <select id="estado_civil" name="estado_civil" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                    <option value="">Seleccione...</option>
                                    @foreach ($estadosCiviles as $estado)
                                        <option value="{{ $estado }}" {{ old('estado_civil') == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('estado_civil')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="profesion" :value="__('Profesión / Oficio')" />
                                <select id="profesion" name="profesion" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                    <option value="">Seleccione...</option>
                                    @foreach ($profesiones as $profesion)
                                        <option value="{{ $profesion }}" {{ old('profesion') == $profesion ? 'selected' : '' }}>{{ $profesion }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('profesion')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="telefono" :value="__('Teléfono')" />
                                <x-text-input id="telefono" class="block mt-1 w-full" type="text" name="telefono" :value="old('telefono')" placeholder="+56 9 1234 5678" />
                                <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="email" :value="__('Correo Electrónico')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="observaciones" :value="__('Observaciones')" />
                                <textarea id="observaciones" name="observaciones" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" rows="3">{{ old('observaciones') }}</textarea>
                                <x-input-error :messages="$errors->get('observaciones')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('socios.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Cancelar</a>
                            <x-primary-button>
                                {{ __('Guardar Socio') }}
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
                // We store the Cleave instance in the 'cleaveRut' variable
                var cleaveRut = new Cleave(rutVisibleInput, {
                    onValueChanged: function (e) {
                        rutHiddenInput.value = e.target.rawValue;
                        
                        var body = e.target.rawValue.slice(0, -1);
                        if (body.length <= 7) {
                            // CORRECCIÓN AQUÍ: Usamos la variable 'cleaveRut' en lugar de 'this'
                            cleaveRut.setBlocks([1, 3, 3, 1]);
                        } else {
                            // CORRECCIÓN AQUÍ: Usamos la variable 'cleaveRut' en lugar de 'this'
                            cleaveRut.setBlocks([2, 3, 3, 1]);
                        }
                    }
                });
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