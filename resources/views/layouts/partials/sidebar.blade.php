{{-- Overlay (solo móvil) --}}
<div
    x-show="sidebarOpen"
    @click="sidebarOpen = false"
    class="fixed inset-0 bg-black bg-opacity-50 z-10 md:hidden"
    style="display: none;">
</div>

{{-- Sidebar --}}
<div
    id="app-sidebar"
    class="w-64 bg-primary-800 text-white flex-shrink-0 transition-transform duration-300 ease-in-out
           fixed inset-y-0 left-0 z-20 md:relative md:translate-x-0 -translate-x-full"
    :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }">

    <div class="p-4 text-center">
        <a href="{{ route('dashboard') }}" class="text-2xl font-bold">
            Santa Rita Conectada
        </a>
    </div>

    <nav class="mt-10 px-2">
        {{-- Enlace a Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="block py-2.5 px-4 rounded transition duration-200 hover:bg-primary-700 {{ request()->routeIs('dashboard') ? 'bg-primary-900' : '' }}">
            Panel Principal
        </a>

        {{-- ===== INICIO: ENLACES PARA SOCIOS ===== --}}
        @role('Socio')
            <div class="my-4 border-t border-primary-700"></div>
            {{-- Mis Aportes apunta al dashboard, que es el centro financiero del socio --}}
            <a href="{{ route('dashboard') }}"
               class="block py-2.5 px-4 rounded transition duration-200 hover:bg-primary-700 {{ request()->routeIs('dashboard') ? 'bg-primary-900' : '' }}">
                Mis Aportes
            </a>
            <a href="{{ route('portal.documentos.index') }}"
               class="block py-2.5 px-4 rounded transition duration-200 hover:bg-primary-700 {{ request()->routeIs('portal.documentos.index') ? 'bg-primary-900' : '' }}">
                Documentos
            </a>
             <a href="{{ route('portal.actas.index') }}"
               class="block py-2.5 px-4 rounded transition duration-200 hover:bg-primary-700 {{ request()->routeIs('portal.actas.index') ? 'bg-primary-900' : '' }}">
                Actas
            </a>
            <a href="{{ route('portal.comunicados.index') }}"
               class="block py-2.5 px-4 rounded transition duration-200 hover:bg-primary-700 {{ request()->routeIs('portal.comunicados.*') ? 'bg-primary-900' : '' }}">
                Comunicados
            </a>
            <a href="{{ route('portal.eventos.index') }}"
               class="block py-2.5 px-4 rounded transition duration-200 hover:bg-primary-700 {{ request()->routeIs('portal.eventos.*') ? 'bg-primary-900' : '' }}">
                Eventos
            </a>
        @endrole
        {{-- ===== FIN: ENLACES PARA SOCIOS ===== --}}

        {{-- --- INICIO DE LA MODIFICACIÓN --- --}}
        {{-- Se corrige la directiva @notrole por la correcta: @unlessrole --}}
        @unlessrole('Socio')
            <div class="my-4 border-t border-primary-700"></div>

            {{-- Enlace a Gestión de Socios --}}
            @role('Secretario|Presidente')
                <a href="{{ route('socios.index') }}"
                   class="block py-2.5 px-4 rounded transition duration-200 hover:bg-primary-700 {{ request()->routeIs('socios.*') ? 'bg-primary-900' : '' }}">
                    Gestión de Socios
                </a>
            @endrole

            {{-- Enlaces para Secretario o Presidente --}}
            @role('Secretario|Presidente')
                <a href="{{ route('actas.index') }}"
                   class="block py-2.5 px-4 rounded transition duration-200 hover:bg-primary-700 {{ request()->routeIs('actas.*') ? 'bg-primary-900' : '' }}">
                    Actas y Documentos
                </a>
                <a href="{{ route('comunicados.index') }}"
                   class="block py-2.5 px-4 rounded transition duration-200 hover:bg-primary-700 {{ request()->routeIs('comunicados.*') ? 'bg-primary-900' : '' }}">
                    Comunicados
                </a>
                <a href="{{ route('eventos.index') }}"
                   class="block py-2.5 px-4 rounded transition duration-200 hover:bg-primary-700 {{ request()->routeIs('eventos.*') ? 'bg-primary-900' : '' }}">
                    Gestión de Eventos
                </a>
            @endrole

            {{-- Enlace para Tesorero o Presidente --}}
            @role('Tesorero|Presidente')
                <a href="{{ route('transacciones.index') }}"
                   class="block py-2.5 px-4 rounded transition duration-200 hover:bg-primary-700 {{ request()->routeIs('transacciones.*') ? 'bg-primary-900' : '' }}">
                    Tesorería
                </a>
                <a href="{{ route('subsidios.index') }}"
                   class="block py-2.5 px-4 rounded transition duration-200 hover:bg-primary-700 {{ request()->routeIs('subsidios.*') ? 'bg-primary-900' : '' }}">
                    Gestión de Subsidios
                </a>
            @endrole

            {{-- Archivo Digital (directiva completa) --}}
            @role('Presidente|Secretario|Tesorero')
                <a href="{{ route('documentos.index') }}"
                   class="block py-2.5 px-4 rounded transition duration-200 hover:bg-primary-700 {{ request()->routeIs('documentos.*') ? 'bg-primary-900' : '' }}">
                    Archivo Digital
                </a>
            @endrole
        @endunlessrole
        {{-- --- FIN DE LA MODIFICACIÓN --- --}}


        <div class="my-4 border-t border-primary-700"></div>
        {{-- Perfil (todos) --}}
        <a href="{{ route('profile.edit') }}"
           class="block py-2.5 px-4 rounded transition duration-200 hover:bg-primary-700 {{ request()->routeIs('profile.edit') ? 'bg-primary-900' : '' }}">
            Mi Perfil
        </a>
    </nav>
</div>