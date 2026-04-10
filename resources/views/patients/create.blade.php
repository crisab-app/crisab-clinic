<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Apertura de Expediente Médico') }}
            </h2>
            <a href="{{ route('patients.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                &larr; Volver al Directorio
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                <form action="{{ route('patients.store') }}" method="POST" class="p-8">
                    @csrf

                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">Información Personal y de Contacto</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <x-input-label for="name" :value="__('Nombre Completo del Paciente *')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required autofocus />
                            </div>

                            <div>
                                <x-input-label for="phone" :value="__('Teléfono Móvil')" />
                                <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full" placeholder="Ej. 998 123 4567" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Correo Electrónico')" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" placeholder="correo@ejemplo.com" />
                            </div>

                            <div>
                                <x-input-label for="date_of_birth" :value="__('Fecha de Nacimiento')" />
                                <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-1 block w-full text-gray-700 dark:text-gray-300" />
                            </div>

                            <div>
                                <x-input-label for="gender" :value="__('Género')" />
                                <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition-colors">
                                    <option value="">-- Seleccionar --</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8 bg-red-50 dark:bg-red-900/10 p-6 rounded-lg border border-red-100 dark:border-red-900/30">
                        <h3 class="text-lg font-bold text-red-800 dark:text-red-400 border-b border-red-200 dark:border-red-900/50 pb-2 mb-4">Información Médica de Emergencia</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="blood_type" :value="__('Grupo Sanguíneo')" class="text-red-700 dark:text-red-300" />
                                <select id="blood_type" name="blood_type" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm transition-colors">
                                    <option value="">-- Desconocido --</option>
                                    <option value="O+">O Positivo (O+)</option>
                                    <option value="O-">O Negativo (O-)</option>
                                    <option value="A+">A Positivo (A+)</option>
                                    <option value="A-">A Negativo (A-)</option>
                                    <option value="B+">B Positivo (B+)</option>
                                    <option value="B-">B Negativo (B-)</option>
                                    <option value="AB+">AB Positivo (AB+)</option>
                                    <option value="AB-">AB Negativo (AB-)</option>
                                </select>
                            </div>

                            <div>
                                <x-input-label for="allergies" :value="__('Alergias (Medicamentos, alimentos, etc.)')" class="text-red-700 dark:text-red-300" />
                                <x-text-input id="allergies" name="allergies" type="text" class="mt-1 block w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-700 dark:bg-gray-900" placeholder="Ej. Penicilina, Nueces" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="notes" :value="__('Antecedentes o Notas Relevantes')" class="text-red-700 dark:text-red-300" />
                                <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm transition-colors" placeholder="Información que el médico deba saber inmediatamente..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end border-t border-gray-200 dark:border-gray-700 pt-6">
                        <x-primary-button class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-lg">
                            Guardar Expediente
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>