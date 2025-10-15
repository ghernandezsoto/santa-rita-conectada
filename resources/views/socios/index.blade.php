<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Socios') }}
            </h2>
            <a href="{{ route('socios.create') }}"
               class="px-4 py-2 bg-accent-500 text-white rounded-lg hover:bg-accent-600 focus:outline-none focus:ring-2 focus:ring-accent-500 focus:ring-offset-2">
                Agregar Nuevo Socio
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if (session('success'))
                    <div class="mb-4 bg-success-100 border border-success-400 text-success-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">¡Éxito!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('password_info'))
                    <div class="mb-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4" role="alert">
                        <p class="font-bold">Contraseña Temporal Generada:</p>
                        <p class="text-2xl font-mono tracking-wider">{{ session('password_info') }}</p>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 bg-danger-100 border border-danger-400 text-danger-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">¡Error!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <div class="mb-4">
                    <form action="{{ route('socios.index') }}" method="GET">
                        <div class="flex items-center">
                            <input type="text" name="search" placeholder="Buscar por nombre o RUT..."
                                   class="border-gray-300 focus:border-accent-500 focus:ring-accent-500 rounded-md shadow-sm w-full sm:w-1/3"
                                   value="{{ request('search') }}">
                            <button type="submit"
                                    class="ml-2 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
                                Buscar
                            </button>
                        </div>
                    </form>
                </div>

                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full bg-white table-fixed">
                        <thead class="bg-primary-800 text-white">
                            <tr>
                                <th class="w-1/12 py-3 px-4 font-semibold text-sm text-left">N°</th>
                                <th class="w-4/12 py-3 px-4 font-semibold text-sm text-left">Nombre</th>
                                <th class="w-2/12 py-3 px-4 font-semibold text-sm text-left">RUT</th>
                                <th class="w-2/12 py-3 px-4 font-semibold text-sm text-left">Contacto</th>
                                <th class="w-1/12 py-3 px-4 font-semibold text-sm text-left">Estado</th>
                                <th class="w-2/12 py-3 px-4 font-semibold text-sm text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @forelse ($socios as $socio)
                                <tr class="border-b hover:bg-base-50">
                                    <td class="py-3 px-4">{{ $socio->id }}</td>
                                    <td class="py-3 px-4">
                                         <span class="block truncate font-medium text-primary-800" title="{{ $socio->nombre }}">
                                            {{ $socio->nombre }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">{{ $socio->rut }}</td>
                                    <td class="py-3 px-4">
                                        <span class="block truncate" title="{{ $socio->email ?: $socio->telefono }}">
                                            {{ $socio->email ?: $socio->telefono }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="py-1 px-3 rounded-full text-xs font-semibold {{ $socio->estado === 'Activo' ? 'bg-success-100 text-success-800' : 'bg-danger-200 text-danger-700' }}">
                                            {{ $socio->estado }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center justify-center gap-4">
                                            <a href="{{ route('socios.show', $socio->id) }}" title="Ver Detalles" class="text-gray-500 hover:text-primary-600">
                                                <x-icons.eye class="w-5 h-5" />
                                            </a>
                                            <a href="{{ route('socios.edit', $socio->id) }}" title="Editar" class="text-gray-500 hover:text-accent-600">
                                                <x-icons.pencil class="w-5 h-5" />
                                            </a>
                                            <form action="{{ route('socios.destroy', $socio->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar a este socio?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Eliminar" class="text-gray-500 hover:text-danger-600">
                                                    <x-icons.trash class="w-5 h-5" />
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-center text-gray-500">No hay socios registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="md:hidden space-y-4">
                    @forelse ($socios as $socio)
                        <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                            <div class="flex justify-between items-start mb-2">
                                <div class="font-bold text-primary-800">{{ $socio->nombre }}</div>
                                <span class="py-1 px-3 rounded-full text-xs font-semibold flex-shrink-0 {{ $socio->estado === 'Activo' ? 'bg-success-100 text-success-800' : 'bg-danger-200 text-danger-700' }}">
                                    {{ $socio->estado }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-600 space-y-1">
                                <p><span class="font-semibold">N° Socio:</span> {{ $socio->id }}</p>
                                <p><span class="font-semibold">RUT:</span> {{ $socio->rut }}</p>
                                <p><span class="font-semibold">Contacto:</span> {{ $socio->email ?: $socio->telefono }}</p>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200 flex items-center justify-end gap-5">
                                <a href="{{ route('socios.show', $socio->id) }}" title="Ver Detalles" class="text-gray-500 hover:text-primary-600">
                                    <x-icons.eye class="w-6 h-6" />
                                </a>
                                <a href="{{ route('socios.edit', $socio->id) }}" title="Editar" class="text-gray-500 hover:text-accent-600">
                                    <x-icons.pencil class="w-6 h-6" />
                                </a>
                                <form action="{{ route('socios.destroy', $socio->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar a este socio?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Eliminar" class="text-gray-500 hover:text-danger-600">
                                        <x-icons.trash class="w-6 h-6" />
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="py-6 text-center text-gray-500">No hay socios registrados.</div>
                    @endforelse
                </div>

                <div class="mt-4">
                    {{ $socios->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>