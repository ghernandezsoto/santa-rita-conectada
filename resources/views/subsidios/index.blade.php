<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Subsidios') }}
            </h2>
            <a href="{{ route('subsidios.create') }}" class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600">
                Registrar Postulación
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">¡Éxito!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-emerald-800 text-white">
                                <tr>
                                    <th class="py-3 px-4 font-semibold text-sm text-left">Socio Postulante</th>
                                    <th class="py-3 px-4 font-semibold text-sm text-left">Nombre del Subsidio</th>
                                    <th class="py-3 px-4 font-semibold text-sm text-left">Monto</th>
                                    <th class="py-3 px-4 font-semibold text-sm text-left">Estado</th>
                                    <th class="py-3 px-4 font-semibold text-sm text-left">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($subsidios as $subsidio)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-4">{{ $subsidio->socio->nombre }}</td>
                                        <td class="py-3 px-4">{{ $subsidio->nombre_subsidio }}</td>
                                        <td class="py-3 px-4">${{ number_format($subsidio->monto_solicitado, 0, ',', '.') }}</td>
                                        <td class="py-3 px-4">
                                            <span class="py-1 px-3 rounded-full text-xs font-semibold
                                                @if($subsidio->estado == 'Aprobado') bg-emerald-100 text-emerald-800 @endif
                                                @if($subsidio->estado == 'Rechazado') bg-red-200 text-red-800 @endif
                                                @if($subsidio->estado == 'Postulando') bg-amber-100 text-amber-800 @endif
                                                @if($subsidio->estado == 'Finalizado') bg-emerald-900 text-white @endif
                                            ">
                                                {{ $subsidio->estado }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 flex items-center gap-2">
                                            <a href="{{ route('subsidios.edit', $subsidio->id) }}" class="text-emerald-800 hover:text-emerald-900 font-medium">Gestionar</a>
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
