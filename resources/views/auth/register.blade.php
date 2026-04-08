<x-guest-layout>
    @if (session('error'))
        <div class="mb-4 font-medium text-sm text-red-600 text-center bg-red-50 p-2 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="mt-4">
        <a href="{{ route('google.redirect') }}" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
            <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            Continuar con Google
        </a>
    </div>

    <div class="mt-6 mb-6 flex items-center justify-between">
        <span class="border-b w-1/5 lg:w-1/4"></span>
        <span class="text-xs text-center text-gray-500 uppercase">o con tu correo</span>
        <span class="border-b w-1/5 lg:w-1/4"></span>
    </div>
    
<form method="POST" action="{{ route('register') }}">
    @csrf

    <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">1. Datos del Negocio</h3>

    <div class="mt-4">
        <x-input-label for="clinic_name" value="Nombre del Consultorio o Clínica" />
        <x-text-input id="clinic_name" class="block mt-1 w-full" type="text" name="clinic_name" :value="old('clinic_name')" required autofocus />
        <x-input-error :messages="$errors->get('clinic_name')" class="mt-2" />
    </div>

    <div class="grid grid-cols-2 gap-4 mt-4">
        <div>
            <x-input-label for="country" value="País" />
            <select id="country" name="country" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                <option value="México">México</option>
                <option value="Colombia">Colombia</option>
                <option value="España">España</option>
                <option value="Argentina">Argentina</option>
                <option value="Otro">Otro</option>
            </select>
            <x-input-error :messages="$errors->get('country')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="timezone" value="Zona Horaria" />
            <select id="timezone" name="timezone" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                <option value="America/Cancun">Cancún / Quintana Roo</option>
                <option value="America/Mexico_City">Ciudad de México (Centro)</option>
                <option value="America/Bogota">Bogotá, Colombia</option>
                <option value="Europe/Madrid">Madrid, España</option>
            </select>
            <x-input-error :messages="$errors->get('timezone')" class="mt-2" />
        </div>
    </div>

    <h3 class="text-lg font-bold text-gray-700 mt-8 mb-4 border-b pb-2">2. Datos del Administrador</h3>

    <div>
        <x-input-label for="name" value="Tu Nombre Completo" />
        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div class="grid grid-cols-2 gap-4 mt-4">
        <div>
            <x-input-label for="phone" value="Teléfono / WhatsApp" />
            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" value="Correo Electrónico" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 mt-4">
        <div>
            <x-input-label for="password" value="Contraseña" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Confirmar Contraseña" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
    </div>

    <div class="flex items-center justify-end mt-6">
        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
            ¿Ya tienes cuenta?
        </a>

        <x-primary-button class="ms-4">
            Comenzar Prueba Gratis
        </x-primary-button>
    </div>
</form>
</x-guest-layout>
