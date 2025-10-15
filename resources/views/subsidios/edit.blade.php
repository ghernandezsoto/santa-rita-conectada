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

                        @if ($subsidio->archivo_path)
                            <p class="mt-2"><strong>Archivo Adjunto:</strong>
                                <a href="{{ asset('storage/' . $subsidio->archivo_path) }}" target="_blank" class="text-primary-600 hover:underline">Ver Documento</a>
                            </p>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('subsidios.update', $subsidio->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="space-y-6">

                            <div>
                                <x-input-label for="estado" :value="__('Cambiar Estado de la Postulación')" />
                                <x-select-input id="estado" name="estado" class="block mt-1 w-full">
                                    <option value="Postulando" {{ old('estado', $subsidio->estado) == 'Postulando' ? 'selected' : '' }}>Postulando</option>
                                    <option value="Aprobado" {{ old('estado', $subsidio->estado) == 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
                                    <option value="Rechazado" {{ old('estado', $subsidio->estado) == 'Rechazado' ? 'selected' : '' }}>Rechazado</option>
                                    <option value="Finalizado" {{ old('estado', $subsidio->estado) == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                                </x-select-input>
                                <x-input-error :messages="$errors->get('estado')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="observaciones_directiva" :value="__('Observaciones de la Directiva (Opcional)')" />
                                <x-textarea-input id="observaciones_directiva" name="observaciones_directiva" class="block mt-1 w-full" rows="4">{{ old('observaciones_directiva', $subsidio->observaciones_directiva) }}</x-textarea-input>
                                <x-input-error :messages="$errors->get('observaciones_directiva')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="archivo" :value="__('Reemplazar Archivo Adjunto (Opcional)')" />
                                <x-file-input id="archivo" name="archivo" class="block mt-1 w-full" />
                                <x-input-error :messages="$errors->get('archivo')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('subsidios.index') }}">
                                <x-secondary-button type="button">
                                    {{ __('Cancelar') }}
                                </x-secondary-button>
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Actualizar Postulación') }}
                            </x-primary-button>
                        </div>
                    </form>

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