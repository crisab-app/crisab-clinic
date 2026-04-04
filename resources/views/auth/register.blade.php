<x-guest-layout>
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
