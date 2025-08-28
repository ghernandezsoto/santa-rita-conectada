<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Santa Rita Conectada') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        {{-- Estado global del sidebar. En escritorio mostramos layout en dos columnas con md:flex. --}}
        <div x-data="{ sidebarOpen: false }"
             @keydown.window.escape="sidebarOpen = false"
             :class="{ 'overflow-hidden': sidebarOpen }"
             class="min-h-screen bg-slate-100 md:flex">

            {{-- Sidebar (controlado por sidebarOpen en móvil, fijo en escritorio) --}}
            @include('layouts.partials.sidebar')

            {{-- Contenedor principal --}}
            <div class="flex-1 flex flex-col">
                {{-- Barra superior --}}
                @include('layouts.navigation')

                {{-- Encabezado de página --}}
                @if (isset($header))
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                {{-- Contenido principal --}}
                <main class="flex-grow">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>
    </body>
</html>
