<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Documentos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Listado de Documentos</h3>
                    <ul class="divide-y divide-gray-200">
                        @forelse ($documentos as $documento)
                            <li class="py-3 flex justify-between items-center">
                                {{-- --- INICIO DE LA MODIFICACIÓN --- --}}
                                {{-- 1. Se corrige la ruta para apuntar al portal del socio. --}}
                                {{-- 2. Se corrige el nombre de la variable a 'nombre_documento'. --}}
                                <a href="{{ route('portal.documentos.show', $documento->id) }}" class="text-primary-600 hover:underline hover:text-primary-800">
                                    {{ $documento->nombre_documento }}
                                </a>
                                {{-- --- FIN DE LA MODIFICACIÓN --- --}}
                                <span class="text-sm text-gray-500 flex-shrink-0 ml-4">{{ $documento->created_at->format('d/m/Y') }}</span>
                            </li>
                        @empty
                            <li class="py-3 text-center text-gray-500">No hay documentos disponibles en este momento.</li>
                        @endforelse
                    </ul>
                    <div class="mt-4">
                        {{ $documentos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>