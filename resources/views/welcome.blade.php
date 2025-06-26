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
<body class="antialiased bg-gray-50">

    {{-- SECCIÓN 1: HÉROE --}}
    <div class="relative h-screen flex items-center justify-center">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=2832&auto=format&fit=crop');"></div>
        <div class="absolute inset-0 bg-black opacity-50"></div>
        
        <div class="relative text-center text-white p-8">
            <h1 class="text-5xl md:text-7xl font-bold leading-tight">
                Santa Rita Conectada
            </h1>
            <p class="text-xl md:text-2xl mt-4 font-light">
                Tu Comunidad Digital Activa
            </p>

            {{-- Botones de Llamada a la Acción --}}
            <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 rounded-lg text-lg font-semibold transition">Ir al Panel</a>
                    @else
                        <a href="{{ route('login') }}" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 rounded-lg text-lg font-semibold transition">Iniciar Sesión</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-8 py-3 bg-gray-700 hover:bg-gray-800 rounded-lg text-lg font-semibold transition">Registrarse</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </div>

    {{-- SECCIÓN 2: CARACTERÍSTICAS --}}
    <div class="bg-white py-16 sm:py-24">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center">
                <p class="text-base font-semibold leading-7 text-blue-600">Nuestra Plataforma</p>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Todo lo que necesitas para una comunidad conectada</h2>
                <p class="mt-6 text-lg leading-8 text-gray-600">
                    Una solución digital integral para modernizar la gestión, mejorar la comunicación y aumentar la transparencia.
                </p>
            </div>

            <div class="mt-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-600 text-white mx-auto">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-gray-900">Registros Digitalizados</h3>
                    <p class="mt-2 text-base text-gray-600">Automatiza el manejo de socios y actas, reduciendo la dependencia del papel y mejorando el acceso a la información.</p>
                </div>

                <div class="text-center">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-600 text-white mx-auto">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-gray-900">Comunicación Unificada</h3>
                    <p class="mt-2 text-base text-gray-600">Establece un canal oficial para el envío masivo de citaciones y noticias importantes a través de múltiples plataformas.</p>
                </div>

                <div class="text-center">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-600 text-white mx-auto">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V6.375c0-.621.504-1.125 1.125-1.125h1.5DB" /></svg>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-gray-900">Tesorería Transparente</h3>
                    <p class="mt-2 text-base text-gray-600">Gestiona las finanzas con balances automáticos, pasarela de pagos y formularios para postulación a subsidios.</p>
                </div>

                <div class="text-center">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-600 text-white mx-auto">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.602-3.751m-.225-1.02a11.959 11.959 0 0 0-9.528-4.471" /></svg>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-gray-900">Seguridad y Respaldo</h3>
                    <p class="mt-2 text-base text-gray-600">Toda la información cuenta con respaldos cifrados en la nube para garantizar la protección y continuidad de los datos.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- SECCIÓN 3: FOOTER --}}
    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
            <p>&copy; 2025 Santa Rita Conectada. Todos los derechos reservados.</p>
            <p class="text-sm text-gray-400 mt-2">Un proyecto de tesis de Gonzalo Hernández y Diego Sepúlveda para la Universidad de Talca.</p>
        </div>
    </footer>

</body>
</html>