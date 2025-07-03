{{-- Barra Lateral (Sidebar) --}}
<div class="w-64 bg-gray-800 text-white flex-shrink-0">
    <div class="p-4 text-center">
        <a href="{{ route('dashboard') }}" class="text-2xl font-bold">
            Santa Rita Conectada
        </a>
    </div>
    <nav class="mt-10">
        {{-- Enlace a Dashboard --}}
        <a href="{{ route('dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('dashboard') ? 'bg-gray-900' : '' }}">
            Dashboard (Inicio)
        </a>

        {{-- Enlace a Gestión de Socios --}}
        <a href="{{ route('socios.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('socios.*') ? 'bg-gray-900' : '' }}">
            Gestión de Socios
        </a>
        {{-- Enlace a Gestión de Actas --}}
        <a href="{{ route('actas.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('actas.*') ? 'bg-gray-900' : '' }}">
            Actas y Documentos
        </a>
        <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 opacity-50 cursor-not-allowed">
            Tesorería
        </a>
        <a href="{{ route('comunicados.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('comunicados.*') ? 'bg-gray-900' : '' }}">
            Comunicados
        </a>

        {{-- Enlace a Mi Perfil --}}
        <a href="{{ route('profile.edit') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('profile.edit') ? 'bg-gray-900' : '' }}">
            Mi Perfil
        </a>
    </nav>
</div>