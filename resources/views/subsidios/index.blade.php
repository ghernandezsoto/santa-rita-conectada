<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Subsidios') }}
            </h2>
            
            {{-- Botón con color accent-500 --}}
            <a href="{{ route('subsidios.create') }}" class="px-4 py-2 bg-accent-500 text-white rounded-lg hover:bg-accent-600 flex items-center gap-2 transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Registrar Postulación
            </a>
        </div>
    </x-slot>

    {{-- Fondo base-100 --}}
    <div class="py-12 bg-base-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Alertas Estandarizadas --}}
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r" role="alert">
                            <p class="font-bold">¡Éxito!</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    {{-- VISTA DE ESCRITORIO (TABLA) --}}
                    <div class="hidden md:block overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full bg-white table-fixed">
                            {{-- Header primary-800 --}}
                            <thead class="bg-primary-800 text-white uppercase text-xs leading-normal">
                                <tr>
                                    <th class="py-3 px-4 text-left w-[20%]">Socio Postulante</th>
                                    <th class="py-3 px-4 text-left w-[25%]">Proyecto</th>
                                    <th class="py-3 px-4 text-center w-[15%]">Monto</th>
                                    <th class="py-3 px-4 text-center w-[15%]">Estado</th>
                                    <th class="py-3 px-4 text-center w-[10%]">Obs.</th>
                                    <th class="py-3 px-4 text-center w-[15%]">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm font-light">
                                @forelse ($subsidios as $subsidio)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                        <td class="py-3 px-4">
                                            <div class="font-medium text-gray-800">{{ $subsidio->socio->nombre }}</div>
                                            <div class="text-xs text-gray-500">{{ $subsidio->socio->rut }}</div>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex items-center">
                                                <span class="truncate" title="{{ $subsidio->nombre_subsidio }}">{{ $subsidio->nombre_subsidio }}</span>
                                                @if($subsidio->archivo_path)
                                                    <a href="{{ asset('storage/' . $subsidio->archivo_path) }}" target="_blank" class="text-accent-500 hover:text-accent-700 ml-2" title="Ver archivo adjunto">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-center font-medium tabular-nums">
                                            ${{ number_format($subsidio->monto_solicitado, 0, ',', '.') }}
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap
                                                @if($subsidio->estado == 'Aprobado') bg-green-100 text-green-800
                                                @elseif($subsidio->estado == 'Rechazado') bg-red-100 text-red-800
                                                @elseif($subsidio->estado == 'Postulando') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ $subsidio->estado }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            @if($subsidio->observaciones_directiva)
                                                <div class="group relative inline-block">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 cursor-help" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <div class="absolute bottom-full right-0 mb-2 hidden group-hover:block w-48 bg-gray-800 text-white text-xs rounded p-2 z-50 shadow-lg text-left">
                                                        {{ Str::limit($subsidio->observaciones_directiva, 100) }}
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <div class="flex item-center justify-center gap-3">
                                                {{-- Botón Editar --}}
                                                <a href="{{ route('subsidios.edit', $subsidio->id) }}" title="Gestionar / Editar" class="text-yellow-500 hover:text-yellow-700 transform hover:scale-110 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </a>
                                                
                                                {{-- Botón Eliminar --}}
                                                <form action="{{ route('subsidios.destroy', $subsidio->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta postulación?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" title="Eliminar" class="text-red-500 hover:text-red-700 transform hover:scale-110 transition">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-8 text-center text-gray-500 bg-gray-50">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                                <p>No hay postulaciones registradas.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- VISTA MÓVIL (TARJETAS) --}}
                    <div class="md:hidden space-y-4">
                        @forelse ($subsidios as $subsidio)
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 border-l-4 
                                @if($subsidio->estado == 'Aprobado') border-l-green-500
                                @elseif($subsidio->estado == 'Rechazado') border-l-red-500
                                @elseif($subsidio->estado == 'Postulando') border-l-yellow-500
                                @else border-l-gray-500 @endif">
                                
                                <div class="flex justify-between items-start">
                                    <div class="overflow-hidden">
                                        <h3 class="font-bold text-primary-800 truncate">{{ $subsidio->nombre_subsidio }}</h3>
                                        <p class="text-xs text-gray-500 mt-1">{{ $subsidio->socio->nombre }}</p>
                                    </div>
                                    <span class="px-2 py-1 rounded-full text-xs font-bold flex-shrink-0
                                        @if($subsidio->estado == 'Aprobado') bg-green-100 text-green-800
                                        @elseif($subsidio->estado == 'Rechazado') bg-red-100 text-red-800
                                        @elseif($subsidio->estado == 'Postulando') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $subsidio->estado }}
                                    </span>
                                </div>

                                <div class="mt-3 text-sm text-gray-700">
                                    <p class="flex justify-between border-b pb-1 mb-1">
                                        <span class="font-semibold text-gray-600">Monto:</span> 
                                        <span>${{ number_format($subsidio->monto_solicitado, 0, ',', '.') }}</span>
                                    </p>
                                    @if($subsidio->archivo_path)
                                        <p class="flex items-center gap-1 mt-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-accent-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                                            <a href="{{ asset('storage/' . $subsidio->archivo_path) }}" target="_blank" class="text-accent-600 hover:text-accent-800 underline text-xs">Ver Adjunto</a>
                                        </p>
                                    @endif
                                </div>

                                @if($subsidio->observaciones_directiva)
                                    <div class="mt-2 p-2 bg-yellow-50 text-xs text-yellow-800 rounded border border-yellow-100">
                                        <strong>Obs:</strong> {{ $subsidio->observaciones_directiva }}
                                    </div>
                                @endif

                                <div class="mt-4 flex justify-end gap-4 border-t pt-3">
                                    <a href="{{ route('subsidios.edit', $subsidio->id) }}" class="text-yellow-600 font-medium text-sm flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                        Editar
                                    </a>
                                    
                                    <form action="{{ route('subsidios.destroy', $subsidio->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 font-medium text-sm flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-gray-500 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                                No hay postulaciones.
                            </div>
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