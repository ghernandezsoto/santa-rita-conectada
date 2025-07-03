<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Actas y Documentos') }}
            </h2>
            <a href="{{ route('actas.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Subir Nueva Acta
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-800 text-white">
                                <tr>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Título del Acta</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Fecha</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Subida por</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @if ($actas->isEmpty())
                                    <tr>
                                        <td colspan="4" class="py-6 text-center text-gray-500">
                                            No hay actas registradas aún.
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($actas as $acta)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="py-3 px-4">{{ $acta->titulo }}</td>
                                            <td class="py-3 px-4">{{ \Carbon\Carbon::parse($acta->fecha)->format('d/m/Y') }}</td>
                                            <td class="py-3 px-4">{{-- Aquí irá el nombre del usuario --}}</td>
                                            <td class="py-3 px-4 flex items-center gap-2">
                                                <a href="#" class="text-green-600 hover:text-green-900 font-medium">Ver</a>
                                                <span class="text-gray-300">|</span>
                                                <a href="#" class="text-red-600 hover:text-red-900 font-medium">Eliminar</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>