<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar ') . $tipo }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('transacciones.update', $transaccion->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <div>
                                <x-input-label for="descripcion" :value="__('Descripción')" />
                                <x-text-input id="descripcion" class="block mt-1 w-full" type="text" name="descripcion" :value="old('descripcion', $transaccion->descripcion)" required autofocus />
                                <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="monto" :value="__('Monto ($)')" />
                                    <x-text-input id="monto" class="block mt-1 w-full" type="text" name="monto" :value="old('monto', number_format($transaccion->monto, 0, ',', '.'))" required />
                                    <x-input-error :messages="$errors->get('monto')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="fecha" :value="__('Fecha de la Transacción')" />
                                    <x-text-input id="fecha" class="block mt-1 w-full" type="date" name="fecha" :value="old('fecha', $transaccion->fecha)" required />
                                    <x-input-error :messages="$errors->get('fecha')" class="mt-2" />
                                </div>
                            </div>

                            <div>
                                <x-input-label for="socio_id" :value="__('Asociar a Socio (Opcional)')" />
                                {{-- ANTES: <select> con clases codificadas --}}
                                <x-select-input id="socio_id" name="socio_id" class="block mt-1 w-full">
                                    <option value="">Ninguno</option>
                                    @foreach ($socios as $socio)
                                        <option value="{{ $socio->id }}" {{ old('socio_id', $transaccion->socio_id) == $socio->id ? 'selected' : '' }}>
                                            {{ $socio->nombre }}
                                        </option>
                                    @endforeach
                                </x-select-input>
                            </div>

                            <div>
                                <x-input-label for="comprobante" :value="__('Reemplazar Comprobante (Opcional)')" />
                                @if ($transaccion->comprobante_path)
                                    <p class="text-sm text-gray-500 mt-1 mb-2">
                                        Comprobante actual:
                                        {{-- ANTES: text-blue-600 --}}
                                        <a href="{{ asset('storage/' . $transaccion->comprobante_path) }}" target="_blank" class="text-primary-600 hover:underline">Ver Comprobante</a>
                                    </p>
                                @endif
                                {{-- ANTES: <input type="file"> con clases codificadas --}}
                                <x-file-input id="comprobante" name="comprobante" class="block mt-1 w-full" />
                                <x-input-error :messages="$errors->get('comprobante')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('transacciones.index') }}">
                                <x-secondary-button type="button">
                                    {{ __('Cancelar') }}
                                </x-secondary-button>
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Actualizar Transacción') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- El script de Cleave.js se mantiene intacto --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Cleave('#monto', {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand',
                delimiter: '.',
                numeralDecimalScale: 0,
                numeralPositiveOnly: true
            });
        });
    </script>
    @endpush
</x-app-layout>