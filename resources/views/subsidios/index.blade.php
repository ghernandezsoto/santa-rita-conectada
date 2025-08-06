<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Subsidios') }}
            </h2>
            {{-- Más adelante, el socio podrá postular. La directiva solo gestiona. --}}
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
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Socio Postulante</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Nombre del Subsidio</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Monto</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Estado</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($subsidios as $subsidio)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-4">{{ $subsidio->socio->nombre }}</td>
                                        <td class="py-3 px-4">{{ $subsidio->nombre_subsidio }}</td>
                                        <td class="py-3 px-4">${{ number_format($subsidio->monto_solicitado, 0, ',', '.') }}</td>
                                        <td class="py-3 px-4">
                                            {{-- Lógica de colores para el estado --}}
                                            <span class="py-1 px-3 rounded-full text-xs
                                                @if($subsidio->estado == 'Aprobado') bg-green-200 text-green-700 @endif
                                                @if($subsidio->estado == 'Rechazado') bg-red-200 text-red-700 @endif
                                                @if($subsidio->estado == 'Postulando') bg-yellow-200 text-yellow-700 @endif
                                                @if($subsidio->estado == 'Finalizado') bg-blue-200 text-blue-700 @endif
                                            ">
                                                {{ $subsidio->estado }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 flex items-center gap-2">
                                            <a href="#" class="text-blue-600 hover:text-blue-900 font-medium">Gestionar</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-6 text-center text-gray-500">
                                            No hay postulaciones a subsidios registradas aún.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                     <div class="mt-4">
                        {{ $subsidios->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>