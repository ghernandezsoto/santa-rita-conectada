<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestionar Postulación') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- INFORMACIÓN DEL PROYECTO --}}
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
                        <h3 class="text-lg font-bold text-blue-800 mb-2">{{ $subsidio->nombre_subsidio }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <p><span class="font-semibold">Socio:</span> {{ $subsidio->socio->nombre }}</p>
                                <p><span class="font-semibold">RUT:</span> {{ $subsidio->socio->rut }}</p>
                            </div>
                            <div>
                                <p><span class="font-semibold">Monto:</span> ${{ number_format($subsidio->monto_solicitado, 0, ',', '.') }}</p>
                                <p><span class="font-semibold">Fecha:</span> {{ $subsidio->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="font-semibold mb-1">Descripción:</p>
                            <p class="text-gray-600 text-sm italic">"{{ $subsidio->descripcion }}"</p>
                        </div>
                        @if ($subsidio->archivo_path)
                            <div class="mt-4">
                                <a href="{{ asset('storage/' . $subsidio->archivo_path) }}" target="_blank" class="inline-flex items-center px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded text-sm text-gray-800 transition">
                                    📎 Ver Documento Adjunto
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- FORMULARIO DE GESTIÓN --}}
                    <form method="POST" action="{{ route('subsidios.update', $subsidio->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Columna Izquierda: Estado --}}
                            <div>
                                <x-input-label for="estado" :value="__('Estado de la Postulación')" />
                                <select id="estado" name="estado" class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm block mt-1 w-full">
                                    <option value="Postulando" {{ old('estado', $subsidio->estado) == 'Postulando' ? 'selected' : '' }}>🟡 Postulando</option>
                                    <option value="Aprobado" {{ old('estado', $subsidio->estado) == 'Aprobado' ? 'selected' : '' }}>🟢 Aprobado</option>
                                    <option value="Rechazado" {{ old('estado', $subsidio->estado) == 'Rechazado' ? 'selected' : '' }}>🔴 Rechazado</option>
                                    <option value="Finalizado" {{ old('estado', $subsidio->estado) == 'Finalizado' ? 'selected' : '' }}>⚪ Finalizado</option>
                                </select>
                                <x-input-error :messages="$errors->get('estado')" class="mt-2" />
                            </div>

                            {{-- Columna Derecha: Archivo --}}
                            <div>
                                <x-input-label for="archivo" :value="__('Reemplazar Archivo (Opcional)')" />
                                <input id="archivo" name="archivo" type="file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none mt-1">
                                <x-input-error :messages="$errors->get('archivo')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Observaciones (Abajo, ancho completo) --}}
                        <div class="mt-6">
                            <x-input-label for="observaciones_directiva" :value="__('Observaciones de la Directiva (Visible para todos)')" />
                            <textarea id="observaciones_directiva" name="observaciones_directiva" rows="3" class="block mt-1 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" placeholder="Ingrese comentarios, razones de rechazo o detalles de aprobación...">{{ old('observaciones_directiva', $subsidio->observaciones_directiva) }}</textarea>
                            <x-input-error :messages="$errors->get('observaciones_directiva')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-8 gap-4">
                            <a href="{{ route('subsidios.index') }}" class="text-gray-600 hover:text-gray-900 underline text-sm">
                                Cancelar
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold shadow">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>