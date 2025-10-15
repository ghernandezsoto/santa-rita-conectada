<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Actas y Documentos') }}
            </h2>
            <a href="{{ route('actas.create') }}" class="px-4 py-2 bg-accent-500 text-white rounded-lg hover:bg-accent-600">
                Subir Nueva Acta
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-base-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="bg-success-100 border border-success-400 text-success-700 px-4 py-3 rounded relative mb-4" role="status" aria-live="polite">
                            <strong class="font-bold">¡Éxito!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-danger-100 border border-danger-400 text-danger-700 px-4 py-3 rounded relative mb-4" role="alert" aria-live="assertive">
                            <strong class="font-bold">¡Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    {{-- TABLA (md+) --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full bg-white table-fixed" role="table" aria-label="Tabla de actas y documentos">
                            <thead class="bg-primary-800 text-white">
                                <tr>
                                    <th scope="col" class="w-1/2 py-3 px-4 font-semibold text-sm text-left">Título del Acta</th>
                                    <th scope="col" class="w-1/6 py-3 px-4 font-semibold text-sm text-left">Fecha</th>
                                    <th scope="col" class="w-1/6 py-3 px-4 font-semibold text-sm text-left">Subida por</th>
                                    <th scope="col" class="w-1/6 py-3 px-4 font-semibold text-sm text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($actas as $acta)
                                    <tr class="border-b hover:bg-base-50">
                                        <td class="py-3 px-4 max-w-0">
                                            <div class="truncate font-medium" title="{{ $acta->titulo }}">
                                                {{ $acta->titulo }}
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 whitespace-nowrap">
                                            {{ $acta->fecha->format('d/m/Y') }}
                                        </td>
                                        <td class="py-3 px-4 whitespace-nowrap">
                                            {{ $acta->user->name ?? 'Usuario no encontrado' }}
                                        </td>
                                        <td class="py-3 px-4">
                                            {{-- --- INICIO DE LA MODIFICACIÓN --- --}}
                                            <div class="flex items-center justify-center gap-4">
                                                <a href="{{ route('actas.show', $acta->id) }}" target="_blank" title="Ver" class="text-gray-400 hover:text-primary-600 transition-colors duration-200">
                                                    <x-icons.eye class="w-5 h-5" />
                                                </a>
                                                <a href="{{ route('actas.edit', $acta->id) }}" title="Editar" class="text-gray-400 hover:text-accent-600 transition-colors duration-200">
                                                    <x-icons.pencil class="w-5 h-5" />
                                                </a>
                                                <form action="{{ route('actas.destroy', $acta->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta acta?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" title="Eliminar" class="text-gray-400 hover:text-danger-600 transition-colors duration-200">
                                                        <x-icons.trash class="w-5 h-5" />
                                                    </button>
                                                </form>
                                            </div>
                                            {{-- --- FIN DE LA MODIFICACIÓN --- --}}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-6 text-center text-gray-500">
                                            No hay actas registradas aún.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- TARJETAS (móvil) --}}
                    <div class="md:hidden space-y-4">
                        @forelse ($actas as $acta)
                            <article class="bg-white border rounded-lg shadow-sm p-4">
                                <h3 class="font-medium text-primary-800 truncate" title="{{ $acta->titulo }}">{{ $acta->titulo }}</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $acta->fecha->format('d/m/Y') }}
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    Subida por: <span class="font-medium">{{ $acta->user->name ?? 'Usuario no encontrado' }}</span>
                                </p>
                                {{-- --- INICIO DE LA MODIFICACIÓN --- --}}
                                <div class="mt-4 pt-4 border-t border-gray-200 flex items-center justify-end gap-6">
                                    <a href="{{ route('actas.show', $acta->id) }}" target="_blank" title="Ver" class="text-gray-400 hover:text-primary-600 transition-colors duration-200">
                                        <x-icons.eye class="w-6 h-6" />
                                    </a>
                                    <a href="{{ route('actas.edit', $acta->id) }}" title="Editar" class="text-gray-400 hover:text-accent-600 transition-colors duration-200">
                                        <x-icons.pencil class="w-6 h-6" />
                                    </a>
                                    <form action="{{ route('actas.destroy', $acta->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta acta?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Eliminar" class="text-gray-400 hover:text-danger-600 transition-colors duration-200">
                                            <x-icons.trash class="w-6 h-6" />
                                        </button>
                                    </form>
                                </div>
                                {{-- --- FIN DE LA MODIFICACIÓN --- --}}
                            </article>
                        @empty
                            <p class="text-center text-gray-500">No hay actas registradas aún.</p>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>