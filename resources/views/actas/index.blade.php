{{-- resources/views/actas/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Actas y Documentos') }}
            </h2>
            {{-- ANTES: bg-amber-500, hover:bg-amber-600 --}}
            <a href="{{ route('actas.create') }}" class="px-4 py-2 bg-accent-500 text-white rounded-lg hover:bg-accent-600">
                Subir Nueva Acta
            </a>
        </div>
    </x-slot>

    {{-- ANTES: bg-slate-100 --}}
    <div class="py-12 bg-base-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Mensajes --}}
                    @if (session('success'))
                        {{-- ANTES: bg-emerald-100 border-emerald-400 text-emerald-700 --}}
                        <div class="bg-success-100 border border-success-400 text-success-700 px-4 py-3 rounded relative mb-4" role="status" aria-live="polite">
                            <strong class="font-bold">¡Éxito!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        {{-- ANTES: bg-red-100 border-red-400 text-red-700 --}}
                        <div class="bg-danger-100 border border-danger-400 text-danger-700 px-4 py-3 rounded relative mb-4" role="alert" aria-live="assertive">
                            <strong class="font-bold">¡Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    {{-- TABLA (md+) --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full bg-white table-fixed" role="table" aria-label="Tabla de actas y documentos">
                            {{-- ANTES: bg-emerald-800 --}}
                            <thead class="bg-primary-800 text-white">
                                <tr>
                                    <th scope="col" class="w-1/2 py-3 px-4 font-semibold text-sm text-left">Título del Acta</th>
                                    <th scope="col" class="w-1/6 py-3 px-4 font-semibold text-sm text-left">Fecha</th>
                                    <th scope="col" class="w-1/6 py-3 px-4 font-semibold text-sm text-left">Subida por</th>
                                    <th scope="col" class="w-1/6 py-3 px-4 font-semibold text-sm text-left">Acciones</th>
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
                                        {{-- ANTES: hover:bg-slate-50 --}}
                                        <tr class="border-b hover:bg-base-50">
                                            <td class="py-3 px-4 max-w-0">
                                                <div class="truncate font-medium" title="{{ $acta->titulo }}">
                                                    {{ $acta->titulo }}
                                                </div>
                                            </td>
                                            <td class="py-3 px-4 whitespace-nowrap">
                                                {{ \Carbon\Carbon::parse($acta->fecha)->format('d/m/Y') }}
                                            </td>
                                            <td class="py-3 px-4 whitespace-nowrap">
                                                {{ $acta->user->name ?? 'Usuario no encontrado' }}
                                            </td>
                                            <td class="py-3 px-4">
                                                <div class="flex items-center gap-2 whitespace-nowrap">
                                                    {{-- ANTES: text-emerald-600 hover:text-emerald-900 --}}
                                                    <a href="{{ route('actas.show', $acta->id) }}" target="_blank" class="text-primary-600 hover:text-primary-900 font-medium">Ver</a>
                                                    <span class="text-gray-300">|</span>
                                                    {{-- ANTES: text-amber-600 hover:text-amber-700 --}}
                                                    <a href="{{ route('actas.edit', $acta->id) }}" class="text-accent-600 hover:text-accent-700 font-medium">Editar</a>
                                                    <span class="text-gray-300">|</span>
                                                    <form action="{{ route('actas.destroy', $acta->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        {{-- ANTES: text-red-600 hover:text-red-700 --}}
                                                        <button type="submit" class="text-danger-600 hover:text-danger-700 font-medium" onclick="return confirm('¿Estás seguro de que quieres eliminar esta acta?')">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                    {{-- TARJETAS (móvil) --}}
                    <div class="md:hidden space-y-4">
                        @if ($actas->isEmpty())
                            <p class="text-center text-gray-500">No hay actas registradas aún.</p>
                        @else
                            @foreach ($actas as $acta)
                                <article class="bg-white border rounded-lg shadow-sm p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            {{-- ANTES: text-emerald-800 --}}
                                            <h3 class="font-medium text-primary-800 truncate" title="{{ $acta->titulo }}">{{ $acta->titulo }}</h3>
                                            <p class="text-sm text-gray-600 mt-1">
                                                {{ \Carbon\Carbon::parse($acta->fecha)->format('d/m/Y') }}
                                            </p>
                                            <p class="text-sm text-gray-600 mt-1">
                                                Subida por: <span class="font-medium">{{ $acta->user->name ?? 'Usuario no encontrado' }}</span>
                                            </p>
                                        </div>
                                        <div class="flex-shrink-0 flex flex-col items-end gap-2">
                                            {{-- ANTES: text-emerald-600 hover:text-emerald-900 --}}
                                            <a href="{{ route('actas.show', $acta->id) }}" target="_blank" class="text-primary-600 hover:text-primary-900 font-medium text-sm">Ver</a>
                                            {{-- ANTES: text-amber-600 hover:text-amber-700 --}}
                                            <a href="{{ route('actas.edit', $acta->id) }}" class="text-accent-600 hover:text-accent-700 font-medium text-sm">Editar</a>
                                            <form action="{{ route('actas.destroy', $acta->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                {{-- ANTES: text-red-600 hover:text-red-700 --}}
                                                <button type="submit" class="text-danger-600 hover:text-danger-700 font-medium text-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar esta acta?')">Eliminar</button>
                                            </form>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>