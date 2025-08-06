<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestionar Postulación a Subsidio') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="border-b pb-4 mb-6">
                        <h3 class="text-xl font-bold mb-2">{{ $subsidio->nombre_subsidio }}</h3>
                        <p><strong>Socio Postulante:</strong> {{ $subsidio->socio->nombre }} (RUT: {{ $subsidio->socio->rut }})</p>
                        <p><strong>Monto Solicitado:</strong> ${{ number_format($subsidio->monto_solicitado, 0, ',', '.') }}</p>
                        <p class="mt-2"><strong>Descripción de la Solicitud:</strong></p>
                        <p class="text-gray-600">{{ $subsidio->descripcion }}</p>
                    </div>

                    <form method="POST" action="{{ route('subsidios.update', $subsidio->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="space-y-6">
                            <div>
                                <x-input-label for="estado" :value="__('Cambiar Estado de la Postulación')" />
                                <select id="estado" name="estado" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="Postulando" {{ old('estado', $subsidio->estado) == 'Postulando' ? 'selected' : '' }}>Postulando</option>
                                    <option value="Aprobado" {{ old('estado', $subsidio->estado) == 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
                                    <option value="Rechazado" {{ old('estado', $subsidio->estado) == 'Rechazado' ? 'selected' : '' }}>Rechazado</option>
                                    <option value="Finalizado" {{ old('estado', $subsidio->estado) == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                                </select>
                                <x-input-error :messages="$errors->get('estado')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="observaciones_directiva" :value="__('Observaciones de la Directiva (Opcional)')" />
                                <textarea id="observaciones_directiva" name="observaciones_directiva" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4">{{ old('observaciones_directiva', $subsidio->observaciones_directiva) }}</textarea>
                                <x-input-error :messages="$errors->get('observaciones_directiva')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('subsidios.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Cancelar</a>
                            <x-primary-button>
                                {{ __('Actualizar Estado') }}
                            </x-primary-button>
                        </div>
                    </form>
                    {{-- Formulario para Eliminar --}}
                    <div class="mt-6 border-t pt-4">
                         <form action="{{ route('subsidios.destroy', $subsidio->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <x-danger-button onclick="return confirm('¿Estás seguro de que quieres eliminar esta postulación? Esta acción no se puede deshacer.')">
                                Eliminar Postulación
                            </x-danger-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>