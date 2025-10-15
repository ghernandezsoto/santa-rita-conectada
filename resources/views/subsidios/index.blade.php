<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Subsidios') }}
            </h2>
            <a href="{{ route('subsidios.create') }}" class="px-4 py-2 bg-accent-500 text-white rounded-lg hover:bg-accent-600">
                Registrar Postulación
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-base-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="bg-success-100 border border-success-400 text-success-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">¡Éxito!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    {{-- VISTA DE ESCRITORITORIO (TABLA) --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full bg-white table-fixed">
                            <thead class="bg-primary-800 text-white">
                                <tr>
                                    <th class="w-3/12 py-3 px-4 font-semibold text-sm text-left">Socio Postulante</th>
                                    <th class="w-4/12 py-3 px-4 font-semibold text-sm text-left">Nombre del Subsidio</th>
                                    <th class="w-2/12 py-3 px-4 font-semibold text-sm text-left">Monto</th>
                                    <th class="w-2/12 py-3 px-4 font-semibold text-sm text-left">Estado</th>
                                    <th class="w-1/12 py-3 px-4 font-semibold text-sm text-left">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($subsidios as $subsidio)
                                    <tr class="border-b hover:bg-base-50">
                                        <td class="py-3 px-4 truncate" title="{{ $subsidio->socio->nombre }}">{{ $subsidio->socio->nombre }}</td>
                                        <td class="py-3 px-4 truncate" title="{{ $subsidio->nombre_subsidio }}">
                                            {{ $subsidio->nombre_subsidio }}
                                            @if($subsidio->archivo_path)
                                                <span class="text-gray-400 ml-1" title="Tiene archivo adjunto">📎</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 whitespace-nowrap">${{ number_format($subsidio->monto_solicitado, 0, ',', '.') }}</td>
                                        <td class="py-3 px-4 max-w-0">
                                            <span class="inline-block truncate py-1 px-3 rounded-full text-xs font-semibold
                                                @if($subsidio->estado == 'Aprobado') bg-success-100 text-success-800 @endif
                                                @if($subsidio->estado == 'Rechazado') bg-danger-200 text-danger-800 @endif
                                                @if($subsidio->estado == 'Postulando') bg-accent-100 text-accent-800 @endif
                                                @if($subsidio->estado == 'Finalizado') bg-base-100 text-base-800 @endif
                                            " title="{{ $subsidio->estado }}">
                                                {{ $subsidio->estado }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <a href="{{ route('subsidios.edit', $subsidio->id) }}" class="text-primary-800 hover:text-primary-900 font-medium">Gestionar</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="py-6 text-center text-gray-500">No hay postulaciones a subsidios registradas aún.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- VISTA MÓVIL (TARJETAS) --}}
                    <div class="md:hidden space-y-4">
                        @forelse ($subsidios as $subsidio)
                            <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-bold text-primary-800 truncate" title="{{ $subsidio->nombre_subsidio }}">{{ $subsidio->nombre_subsidio }}</h3>
                                        <p class="text-sm text-gray-600 truncate" title="{{ $subsidio->socio->nombre }}">Postulante: {{ $subsidio->socio->nombre }}</p>
                                    </div>

                                    <span class="ml-2 flex-shrink-0 inline-block truncate py-1 px-2 rounded-full text-xs font-semibold
                                        @if($subsidio->estado == 'Aprobado') bg-success-100 text-success-800 @endif
                                        @if($subsidio->estado == 'Rechazado') bg-danger-200 text-danger-800 @endif
                                        @if($subsidio->estado == 'Postulando') bg-accent-100 text-accent-800 @endif
                                        @if($subsidio->estado == 'Finalizado') bg-base-100 text-base-800 @endif
                                    " title="{{ $subsidio->estado }}">
                                        {{ $subsidio->estado }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-600 border-t pt-2 mt-2">
                                    <p><span class="font-semibold">Monto Solicitado:</span> ${{ number_format($subsidio->monto_solicitado, 0, ',', '.') }}</p>
                                </div>
                                <div class="mt-4 flex items-center justify-between">
                                    <div>
                                        @if($subsidio->archivo_path)
                                            <span class="text-gray-500 text-sm flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a3 3 0 006 0V7a1 1 0 112 0v4a5 5 0 01-10 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd" /></svg>
                                                Archivo adjunto
                                            </span>
                                        @endif
                                    </div>
                                    <a href="{{ route('subsidios.edit', $subsidio->id) }}" class="px-4 py-2 bg-primary-700 text-white text-sm font-medium rounded-lg hover:bg-primary-800">Gestionar</a>
                                </div>
                            </div>
                        @empty
                            <div class="py-6 text-center text-gray-500">No hay postulaciones a subsidios registradas aún.</div>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        {{ $subsidios->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>