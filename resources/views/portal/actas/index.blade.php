<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Actas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Listado de Actas</h3>
                    <ul class="divide-y divide-gray-200">
                        @forelse ($actas as $acta)
                            <li class="py-3 flex justify-between items-center">
                                <a href="{{ route('actas.show', $acta->id) }}" target="_blank" class="text-primary-600 hover:underline hover:text-primary-800">
                                    {{ $acta->titulo }}
                                </a>
                                <span class="text-sm text-gray-500 flex-shrink-0 ml-4">{{ $acta->fecha->format('d/m/Y') }}</span>
                            </li>
                        @empty
                            <li class="py-3 text-center text-gray-500">No hay actas disponibles en este momento.</li>
                        @endforelse
                    </ul>
                     <div class="mt-4">
                        {{ $actas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>