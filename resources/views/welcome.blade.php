<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Santa Rita Conectada</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 font-sans text-gray-900">

    {{-- ====================================================== --}}
    {{-- SECCIÓN 1: HÉROE (PORTADA) --}}
    {{-- ====================================================== --}}
    <div class="relative h-screen flex items-center justify-center">
        {{-- Imagen de fondo --}}
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=2832&auto=format&fit=crop');"></div>

        {{-- Overlay oscuro para mejorar lectura --}}
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-gray-900/90"></div>

        <div class="relative text-center text-white p-8 max-w-4xl mx-auto">
            <h1 class="text-5xl md:text-7xl font-bold leading-tight tracking-tight mb-6">
                Santa Rita Conectada
            </h1>
            <p class="text-xl md:text-2xl font-light text-gray-200 mb-10 max-w-2xl mx-auto">
                Plataforma digital integral para la modernización, transparencia y participación de nuestra comunidad.
            </p>

            {{-- Botones de Llamada a la Acción --}}
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 rounded-full text-lg font-semibold transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            Ir al Panel de Gestión
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 rounded-full text-lg font-semibold transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            Ingresar (Socios y Directiva)
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </div>

    {{-- ====================================================== --}}
    {{-- SECCIÓN 2: CARACTERÍSTICAS (GRID) --}}
    {{-- ====================================================== --}}
    <div class="bg-white py-24 sm:py-32">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-base font-semibold leading-7 text-blue-600 uppercase tracking-wide">Innovación Social</h2>
                <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                    Transformación Digital para la Junta de Vecinos
                </p>
                <p class="mt-6 text-lg leading-8 text-gray-600">
                    Hemos reemplazado los procesos manuales por un ecosistema tecnológico seguro y eficiente.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                {{-- Card 1 --}}
                <div class="relative pl-4 border-l-4 border-blue-100 hover:border-blue-600 transition-colors duration-300">
                    <div class="text-blue-600 mb-4">
                        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Gestión Documental</h3>
                    <p class="mt-2 text-gray-600 text-sm">Digitalización completa de actas, estatutos y fichas de socios. Acceso seguro y descargas controladas.</p>
                </div>

                {{-- Card 2 --}}
                <div class="relative pl-4 border-l-4 border-blue-100 hover:border-blue-600 transition-colors duration-300">
                    <div class="text-blue-600 mb-4">
                        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Notificaciones Push</h3>
                    <p class="mt-2 text-gray-600 text-sm">Comunicación oficial en tiempo real. Recibe citaciones y noticias importantes directamente en tu celular.</p>
                </div>

                {{-- Card 3 --}}
                <div class="relative pl-4 border-l-4 border-blue-100 hover:border-blue-600 transition-colors duration-300">
                    <div class="text-blue-600 mb-4">
                        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V6.375c0-.621.504-1.125 1.125-1.125h1.5" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Transparencia Activa</h3>
                    <p class="mt-2 text-gray-600 text-sm">Visualización clara de ingresos y egresos. Cada socio puede ver su historial de aportes y comprobantes.</p>
                </div>

                {{-- Card 4 --}}
                <div class="relative pl-4 border-l-4 border-blue-100 hover:border-blue-600 transition-colors duration-300">
                    <div class="text-blue-600 mb-4">
                        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.602-3.751m-.225-1.02a11.959 11.959 0 0 0-9.528-4.471" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Seguridad Cloud</h3>
                    <p class="mt-2 text-gray-600 text-sm">Infraestructura robusta con respaldos automatizados diarios en Amazon AWS S3 para proteger la historia de la junta.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ====================================================== --}}
    {{-- SECCIÓN 3: PREGUNTAS FRECUENTES (NUEVO) --}}
    {{-- ====================================================== --}}
    <div class="bg-gray-50 py-24 sm:py-32 border-t border-gray-200">
        <div class="max-w-4xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-base font-semibold leading-7 text-blue-600 uppercase tracking-wide">Ayuda y Soporte</h2>
                <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                    Preguntas Frecuentes
                </p>
                <p class="mt-4 text-lg text-gray-600">
                    Resolvemos las principales dudas sobre el uso de la plataforma.
                </p>
            </div>

            <div class="space-y-4">
                {{-- FAQ 1 --}}
                <div x-data="{ open: false }" class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-all">
                    <button @click="open = !open" class="flex w-full items-center justify-between px-6 py-5 text-left focus:outline-none">
                        <span class="text-lg font-medium text-gray-900">¿Cómo puedo obtener mi contraseña?</span>
                        <span class="ml-6 flex-shrink-0">
                            <svg :class="{'rotate-180': open}" class="h-6 w-6 text-gray-400 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </button>
                    <div x-show="open" style="display: none;" class="px-6 pb-6 text-gray-600 leading-relaxed">
                        Debes estar inscrito oficialmente en el libro de socios. Acércate a la directiva para que registren tu correo electrónico en el sistema. Una vez hecho esto, recibirás una clave de acceso temporal que podrás cambiar después.
                    </div>
                </div>

                {{-- FAQ 2 --}}
                <div x-data="{ open: false }" class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-all">
                    <button @click="open = !open" class="flex w-full items-center justify-between px-6 py-5 text-left focus:outline-none">
                        <span class="text-lg font-medium text-gray-900">¿Puedo pagar las cuotas a través de la web?</span>
                        <span class="ml-6 flex-shrink-0">
                            <svg :class="{'rotate-180': open}" class="h-6 w-6 text-gray-400 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </button>
                    <div x-show="open" style="display: none;" class="px-6 pb-6 text-gray-600 leading-relaxed">
                        Actualmente, la plataforma funciona como un <strong>visualizador de transparencia</strong>. Puedes ver tu historial de pagos y descargar comprobantes, pero los pagos se siguen realizando de forma presencial o por transferencia directa a la cuenta de la Junta de Vecinos.
                    </div>
                </div>

                {{-- FAQ 3 --}}
                <div x-data="{ open: false }" class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-all">
                    <button @click="open = !open" class="flex w-full items-center justify-between px-6 py-5 text-left focus:outline-none">
                        <span class="text-lg font-medium text-gray-900">¿Cómo descargo un certificado de residencia?</span>
                        <span class="ml-6 flex-shrink-0">
                            <svg :class="{'rotate-180': open}" class="h-6 w-6 text-gray-400 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </button>
                    <div x-show="open" style="display: none;" class="px-6 pb-6 text-gray-600 leading-relaxed">
                        Ingresa a tu portal de socio con tu RUT y clave. Dirígete a la sección <strong>"Documentos"</strong>. Allí podrás encontrar certificados generales. Si necesitas uno específico firmado por la presidenta, contáctanos directamente a través de los datos al pie de página.
                    </div>
                </div>

                {{-- FAQ 4 --}}
                <div x-data="{ open: false }" class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-all">
                    <button @click="open = !open" class="flex w-full items-center justify-between px-6 py-5 text-left focus:outline-none">
                        <span class="text-lg font-medium text-gray-900">¿Cómo me entero de las reuniones?</span>
                        <span class="ml-6 flex-shrink-0">
                            <svg :class="{'rotate-180': open}" class="h-6 w-6 text-gray-400 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </button>
                    <div x-show="open" style="display: none;" class="px-6 pb-6 text-gray-600 leading-relaxed">
                        ¡Descarga nuestra aplicación móvil! Recibirás notificaciones "Push" instantáneas cada vez que haya una citación a reunión, un corte de agua programado o cualquier noticia urgente de la comunidad. También puedes revisar la sección "Comunicados" en esta web.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ====================================================== --}}
    {{-- SECCIÓN 4: FOOTER --}}
    {{-- ====================================================== --}}
    <footer class="bg-gray-900 text-white pt-16 pb-8 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">

                {{-- Columna 1: Branding --}}
                <div>
                    <h4 class="text-2xl font-bold mb-4 text-blue-500">Santa Rita Conectada</h4>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Plataforma oficial de la Junta de Vecinos N°4 de Santa Rita. Un esfuerzo por modernizar nuestra comunidad y acercar a los vecinos a través de la tecnología.
                    </p>
                </div>

                {{-- Columna 2: Contacto --}}
                <div>
                    <h4 class="text-lg font-semibold mb-4 uppercase tracking-wider border-b border-gray-700 pb-2 inline-block">Contacto Directiva</h4>
                    <ul class="space-y-3 text-gray-300 text-sm">
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-blue-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <span>Camino Principal S/N, Sector Santa Rita, Comuna de Pelarco.</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            <span>juntavecinos4.pelarco@gmail.com</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            <span>+56 9 1234 5678 (Presidente)</span>
                        </li>
                    </ul>
                </div>

                {{-- Columna 3: Créditos --}}
                <div>
                    <h4 class="text-lg font-semibold mb-4 uppercase tracking-wider border-b border-gray-700 pb-2 inline-block">Créditos Académicos</h4>
                    <p class="text-gray-400 text-sm mb-2">Memoria de Título de Ingeniero en Informática Empresarial.</p>
                    <p class="text-gray-500 text-xs">
                        Desarrollado por <strong>Gonzalo Hernández</strong> y <strong>Diego Sepúlveda</strong>.
                        <br>Universidad de Talca, 2025.
                    </p>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 text-center">
                <p class="text-gray-500 text-sm">&copy; 2025 Santa Rita Conectada. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

</body>
</html>