<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Adminconsul - Gestión Médica Inteligente</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased bg-gray-50 text-gray-800 min-h-screen flex flex-col">
    
    <nav class="w-full bg-white shadow-sm py-4 px-4 sm:px-6 flex flex-wrap justify-between items-center gap-4">
        <div class="text-xl sm:text-2xl font-bold text-indigo-600">
            Adminconsul
        </div>
        
        <div class="flex items-center gap-3 sm:gap-4">
            @auth
                <a href="{{ url('/dashboard') }}" class="text-sm sm:text-base text-gray-600 hover:text-indigo-600 font-semibold">Ir a mi Panel</a>
            @else
                <a href="{{ route('login') }}" class="text-sm sm:text-base text-gray-600 hover:text-indigo-600 font-semibold">Iniciar Sesión</a>
                <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-md text-sm sm:text-base font-semibold hover:bg-indigo-700 transition text-center">Prueba Gratis</a>
            @endauth
        </div>
    </nav>

    <main class="flex-grow flex items-center justify-center text-center px-4 py-10 sm:py-0">
        <div class="max-w-3xl">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-gray-900 mb-4 sm:mb-6 leading-tight">
                El sistema operativo para tu clínica y consultorio.
            </h1>
            
            <p class="text-base sm:text-lg md:text-xl text-gray-600 mb-8 sm:mb-10 px-2 sm:px-0">
                Gestiona agendas, pacientes, múltiples consultorios y recursos médicos desde un solo lugar. Seguro, rápido y siempre disponible.
            </p>
            
            @guest
                <a href="{{ route('register') }}" class="inline-block w-full sm:w-auto bg-indigo-600 text-white px-6 py-3 sm:px-8 sm:py-4 rounded-lg text-base sm:text-lg font-bold hover:bg-indigo-700 transition shadow-lg">
                    Comienza a digitalizar tu clínica hoy
                </a>
            @endguest
        </div>
    </main>

    <footer class="w-full text-center py-6 text-gray-500 text-xs sm:text-sm px-4">
        &copy; {{ date('Y') }} Adminconsul. Desarrollado por CRISAB. Todos los derechos reservados.
    </footer>

</body>
</html>