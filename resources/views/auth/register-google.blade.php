<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900">¡Hola, {{ session('google_name') }}!</h2>
        <p class="text-sm text-gray-600 mt-2">Ya casi terminamos. Para configurar tu cuenta de <strong>{{ session('google_email') }}</strong>, necesitamos los datos de tu clínica.</p>
    </div>

    <form method="POST" action="{{ route('register.google.store') }}">
        @csrf

        <div class="mt-4">
            <x-input-label for="phone" value="Tu Teléfono (WhatsApp)" />
            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required autofocus autocomplete="tel" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="clinic_name" value="Nombre de tu Clínica o Consultorio" />
            <x-text-input id="clinic_name" class="block mt-1 w-full" type="text" name="clinic_name" :value="old('clinic_name')" required />
            <x-input-error :messages="$errors->get('clinic_name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="country" value="País" />
            <select id="country" name="country" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                <option value="México">México</option>
                <option value="Colombia">Colombia</option>
                <option value="Argentina">Argentina</option>
                <option value="Perú">Perú</option>
                <option value="Otro">Otro</option>
            </select>
            <x-input-error :messages="$errors->get('country')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="timezone" value="Zona Horaria (Para tus citas)" />
            <select id="timezone" name="timezone" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                <option value="America/Cancun">América/Cancún</option>
                <option value="America/Mexico_City">América/Ciudad de México</option>
                <option value="America/Bogota">América/Bogotá</option>
                <option value="America/Argentina/Buenos_Aires">América/Buenos Aires</option>
                <option value="America/Lima">América/Lima</option>
            </select>
            <x-input-error :messages="$errors->get('timezone')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button class="w-full justify-center">
                Crear Mi Clínica y Entrar
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>