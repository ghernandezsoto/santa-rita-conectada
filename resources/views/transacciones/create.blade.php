<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Nuevo ') . $tipo }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('transacciones.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="tipo" value="{{ $tipo }}">

                        <div class="space-y-6">
                            <div>
                                <x-input-label for="descripcion" :value="__('Descripción')" />
                                <x-text-input id="descripcion" class="block mt-1 w-full" type="text" name="descripcion" :value="old('descripcion')" required autofocus />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="monto" :value="__('Monto ($)')" />
                                    <x-text-input id="monto" class="block mt-1 w-full" type="number" name="monto" :value="old('monto')" required step="1" />
                                </div>
                                <div>
                                    <x-input-label for="fecha" :value="__('Fecha de la Transacción')" />
                                    <x-text-input id="fecha" class="block mt-1 w-full" type="date" name="fecha" :value="old('fecha', date('Y-m-d'))" required />
                                </div>
                            </div>

                            {{-- CAMPO NUEVO PARA VINCULAR SOCIO --}}
                            <div>
                                <x-input-label for="socio_id" :value="__('Asociar a Socio (Opcional)')" />
                                <select id="socio_id" name="socio_id" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                    <option value="">Ninguno</option>
                                    @foreach ($socios as $socio)
                                        <option value="{{ $socio->id }}" {{ old('socio_id') == $socio->id ? 'selected' : '' }}>
                                            {{ $socio->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-input-label for="comprobante" :value="__('Adjuntar Comprobante (Opcional)')" />
                                <input id="comprobante" name="comprobante" type="file" class="block mt-1 w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100"/>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('transacciones.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Cancelar</a>
                            <x-primary-button>
                                {{ __('Guardar Transacción') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>