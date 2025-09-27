<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Santa Rita Conectada') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
        <script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js" defer></script>
        
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <script defer src="https://cdn.jsdelivr.net/npm/@floating-ui/core@1.6.0"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/@floating-ui/dom@1.6.3"></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">

        <script>
            document.addEventListener("trix-before-initialize", () => {
                // 1. Mantenemos la traducción a Español
                Trix.config.lang = {
                    attachFiles: "Adjuntar archivos",
                    bold: "Negrita",
                    bullets: "Viñetas",
                    byte: "Byte",
                    bytes: "Bytes",
                    captionPlaceholder: "Añadir una descripción…",
                    code: "Código",
                    heading1: "Título 1",
                    indent: "Aumentar sangría",
                    italic: "Cursiva",
                    kilobyte: "KB",
                    link: "Enlace",
                    numbers: "Números",
                    outdent: "Disminuir sangría",
                    quote: "Cita",
                    redo: "Rehacer",
                    remove: "Quitar",
                    strike: "Tachado",
                    undo: "Deshacer",
                    unlink: "Quitar enlace",
                    url: "URL",
                    urlPlaceholder: "Introduce una URL…",
                    GB: "GB", "PB": "PB", "TB": "TB"
                };

                // 2. Definimos una barra de herramientas simple y funcional
                Trix.config.toolbar.getDefaultHTML = () => `
                    <div class="trix-button-row">
                        <span class="trix-button-group trix-button-group--text-tools" data-trix-button-group="text-tools">
                            <button type="button" class="trix-button" data-trix-attribute="bold" data-trix-key="b" title="Negrita">Negrita</button>
                            <button type="button" class="trix-button" data-trix-attribute="italic" data-trix-key="i" title="Cursiva">Cursiva</button>
                            <button type="button" class="trix-button" data-trix-attribute="strike" title="Tachado">Tachado</button>
                        </span>
                        <span class="trix-button-group trix-button-group--block-tools" data-trix-button-group="block-tools">
                            <button type="button" class="trix-button" data-trix-attribute="heading1" title="Título">Título</button>
                            <button type="button" class="trix-button" data-trix-attribute="bullet" title="Viñetas">Viñetas</button>
                            <button type="button" class="trix-button" data-trix-attribute="number" title="Números">Números</button>
                        </span>
                    </div>
                `;
            });
        </script>
        <div class="min-h-screen bg-slate-100 flex" x-data="{ sidebarOpen: false }">

            @include('layouts.partials.sidebar')

            <div class="flex-1 flex flex-col">

                @include('layouts.navigation')

                @if (isset($header))
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <main class="flex-grow">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>