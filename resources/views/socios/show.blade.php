<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle del Socio') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-2xl font-bold mb-4">{{ $socio->nombre }}</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <p><strong>RUT:</strong> {{ $socio->rut }}</p>
                        <p><strong>Estado:</strong> <span class="py-1 px-2 rounded-full text-xs {{ $socio->estado === 'Activo' ? 'bg-green-200 text-green-700' : 'bg-red-200 text-red-700' }}">{{ $socio->estado }}</span></p>
                        <p><strong>Fecha de Ingreso:</strong> {{ \Carbon\Carbon::parse($socio->fecha_ingreso)->format('d/m/Y') }}</p>
                        <p><strong>Edad:</strong> {{ $socio->edad ?: 'No especificado' }}</p>
                        <p><strong>Estado Civil:</strong> {{ $socio->estado_civil ?: 'No especificado' }}</p>
                        <p><strong>Profesión:</strong> {{ $socio->profesion ?: 'No especificado' }}</p>
                        <p class="md:col-span-2"><strong>Domicilio:</strong> {{ $socio->domicilio }}</p>
                        <p><strong>Teléfono:</strong> {{ $socio->telefono ?: 'No especificado' }}</p>
                        <p><strong>Correo Electrónico:</strong> {{ $socio->email ?: 'No especificado' }}</p>
                        <div class="md:col-span-2">
                            <p><strong>Observaciones:</strong></p>
                            <p class="mt-1 text-gray-600">{{ $socio->observaciones ?: 'Sin observaciones.' }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('socios.index') }}" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700">
                            Volver a la Lista
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>