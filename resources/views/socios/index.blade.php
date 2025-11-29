<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Socios') }}
            </h2>
            
            {{-- Botón con diseño estandarizado (Accent + Icono) --}}
            <a href="{{ route('socios.create') }}"
               class="px-4 py-2 bg-accent-500 text-white rounded-lg hover:bg-accent-600 flex items-center gap-2 transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Agregar Nuevo Socio
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
                        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-r" role="alert">
                            <strong class="font-bold">¡Éxito!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('password_info'))
                        <div class="mb-6 bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded-r" role="alert">
                            <p class="font-bold">Contraseña Temporal Generada:</p>
                            <p class="text-2xl font-mono tracking-wider mt-1">{{ session('password_info') }}</p>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-r" role="alert">
                            <strong class="font-bold">¡Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    {{-- Buscador --}}
                    <div class="mb-6">
                        <form action="{{ route('socios.index') }}" method="GET">
                            <div class="flex items-center gap-2">
                                <div class="relative w-full sm:w-1/3">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="text" name="search" placeholder="Buscar por nombre o RUT..."
                                           class="pl-10 border-gray-300 focus:border-accent-500 focus:ring-accent-500 rounded-lg shadow-sm w-full"
                                           value="{{ request('search') }}">
                                </div>
                                <button type="submit"
                                        class="px-4 py-2 bg-primary-800 text-white rounded-lg hover:bg-primary-900 transition shadow-sm">
                                    Buscar
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- VISTA DE ESCRITORIO (TABLA) --}}
                    <div class="hidden md:block overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full bg-white table-fixed">
                            {{-- Header primary-800 uppercase --}}
                            <thead class="bg-primary-800 text-white uppercase text-xs leading-normal">
                                <tr>
                                    <th class="w-1/12 py-3 px-4 font-semibold text-left">N°</th>
                                    <th class="w-4/12 py-3 px-4 font-semibold text-left">Nombre</th>
                                    <th class="w-2/12 py-3 px-4 font-semibold text-left">RUT</th>
                                    <th class="w-2/12 py-3 px-4 font-semibold text-left">Contacto</th>
                                    <th class="w-1/12 py-3 px-4 font-semibold text-left">Estado</th>
                                    <th class="w-2/12 py-3 px-4 font-semibold text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm font-light">
                                @forelse ($socios as $socio)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                        <td class="py-3 px-4 font-medium">{{ $socio->id }}</td>
                                        <td class="py-3 px-4">
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                <span class="truncate font-medium text-gray-800" title="{{ $socio->nombre }}">
                                                    {{ $socio->nombre }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4">{{ $socio->rut }}</td>
                                        <td class="py-3 px-4">
                                            <span class="block truncate" title="{{ $socio->email ?: $socio->telefono }}">
                                                {{ $socio->email ?: $socio->telefono }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            @if($socio->estado === 'Activo')
                                                <span class="py-1 px-3 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                    {{ $socio->estado }}
                                                </span>
                                            @else
                                                <span class="py-1 px-3 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                    {{ $socio->estado }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex items-center justify-center gap-3">
                                                {{-- Ver --}}
                                                <a href="{{ route('socios.show', $socio->id) }}" title="Ver Detalles" class="text-blue-500 hover:text-blue-700 transform hover:scale-110 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                {{-- Editar --}}
                                                <a href="{{ route('socios.edit', $socio->id) }}" title="Editar" class="text-yellow-500 hover:text-yellow-700 transform hover:scale-110 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </a>
                                                {{-- Eliminar --}}
                                                <form action="{{ route('socios.destroy', $socio->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar a este socio?');" class="inline">
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
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                                <p>No hay socios registrados.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- VISTA MÓVIL (TARJETAS) --}}
                    <div class="md:hidden space-y-4">
                        @forelse ($socios as $socio)
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="font-bold text-primary-800 text-lg">{{ $socio->nombre }}</div>
                                    @if($socio->estado === 'Activo')
                                        <span class="py-1 px-2 rounded-full text-xs font-semibold flex-shrink-0 bg-green-100 text-green-800">
                                            {{ $socio->estado }}
                                        </span>
                                    @else
                                        <span class="py-1 px-2 rounded-full text-xs font-semibold flex-shrink-0 bg-red-100 text-red-800">
                                            {{ $socio->estado }}
                                        </span>
                                    @endif
                                </div>
                                <div class="text-sm text-gray-600 space-y-1">
                                    <p><span class="font-semibold">N° Socio:</span> {{ $socio->id }}</p>
                                    <p><span class="font-semibold">RUT:</span> {{ $socio->rut }}</p>
                                    <p class="truncate"><span class="font-semibold">Contacto:</span> {{ $socio->email ?: $socio->telefono }}</p>
                                </div>
                                
                                <div class="mt-4 pt-3 border-t border-gray-200 flex items-center justify-end gap-4">
                                    <a href="{{ route('socios.show', $socio->id) }}" class="text-blue-600 font-medium text-sm flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        Ver
                                    </a>
                                    <a href="{{ route('socios.edit', $socio->id) }}" class="text-yellow-600 font-medium text-sm flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                        Editar
                                    </a>
                                    <form action="{{ route('socios.destroy', $socio->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar a este socio?');" class="inline">
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
                                No hay socios registrados.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        {{ $socios->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>