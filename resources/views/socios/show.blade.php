<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle del Socio') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-base-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Columna de Información Principal del Socio --}}
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900">{{ $socio->nombre }}</h3>
                        <p class="mt-1 text-sm text-gray-500">{{ $socio->rut }}</p>

                        <div class="mt-6 border-t border-gray-200 pt-6">
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <span class="py-1 px-3 rounded-full text-xs font-semibold {{ $socio->estado === 'Activo' ? 'bg-success-100 text-success-800' : 'bg-danger-200 text-danger-700' }}">
                                            {{ $socio->estado }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Correo Electrónico</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $socio->email ?? 'No registrado' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $socio->telefono ?? 'No registrado' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Domicilio</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $socio->domicilio }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Fecha de Ingreso</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($socio->fecha_ingreso)->format('d/m/Y') }}</dd>
                                </div>
                            </dl>
                        </div>
                        <div class="mt-6 flex flex-wrap gap-2">
                            <a href="{{ route('socios.edit', $socio->id) }}">
                                <x-primary-button>Editar Socio</x-primary-button>
                            </a>
                            <a href="{{ route('socios.index') }}">
                                <x-secondary-button type="button">Volver a la Lista</x-secondary-button>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Columna de Historial de Aportes --}}
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-semibold mb-4">Historial de Aportes y Transacciones</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white">
                                    <thead class="bg-primary-800 text-white">
                                        <tr>
                                            <th class="py-3 px-4 font-semibold text-sm text-left">Fecha</th>
                                            <th class="py-3 px-4 font-semibold text-sm text-left">Descripción</th>
                                            <th class="py-3 px-4 font-semibold text-sm text-left">Monto</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-700">
                                        @forelse ($socio->transacciones as $transaccion)
                                            <tr class="border-b hover:bg-base-50">
                                                <td class="py-3 px-4">{{ \Carbon\Carbon::parse($transaccion->fecha)->format('d/m/Y') }}</td>
                                                <td class="py-3 px-4">{{ $transaccion->descripcion }}</td>
                                                <td class="py-3 px-4 font-medium {{ $transaccion->tipo == 'Ingreso' ? 'text-success-600' : 'text-danger-600' }}">
                                                    {{ $transaccion->tipo == 'Ingreso' ? '+' : '-' }} ${{ number_format($transaccion->monto, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="py-6 text-center text-gray-500">
                                                    Este socio no tiene transacciones registradas.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>