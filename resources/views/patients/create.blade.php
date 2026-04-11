<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Alta de Paciente / Cliente') }}
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

                    <div class="mb-8 flex justify-center">
                        <div class="inline-flex bg-gray-100 dark:bg-gray-900 rounded-lg p-1 border border-gray-200 dark:border-gray-700">
                            <label class="cursor-pointer">
                                <input type="radio" name="client_type" value="fisica" class="peer sr-only" checked onchange="toggleClientType()">
                                <div class="px-6 py-2 rounded-md text-sm font-medium text-gray-500 dark:text-gray-400 peer-checked:bg-white dark:peer-checked:bg-gray-800 peer-checked:text-indigo-600 dark:peer-checked:text-indigo-400 peer-checked:shadow-sm transition-all">
                                    Persona Física (Paciente)
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="client_type" value="moral" class="peer sr-only" onchange="toggleClientType()">
                                <div class="px-6 py-2 rounded-md text-sm font-medium text-gray-500 dark:text-gray-400 peer-checked:bg-white dark:peer-checked:bg-gray-800 peer-checked:text-indigo-600 dark:peer-checked:text-indigo-400 peer-checked:shadow-sm transition-all">
                                    Empresa (Facturación)
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">Información General</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <x-input-label for="name" id="label_name" :value="__('Nombre Completo del Paciente *')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required autofocus />
                            </div>

                            <div>
                                <x-input-label for="phone" :value="__('Teléfono de Contacto')" />
                                <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full" placeholder="Ej. 998 123 4567" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Correo Electrónico')" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" placeholder="correo@ejemplo.com" />
                            </div>

                            <div class="fisica-only">
                                <x-input-label for="curp" :value="__('CURP')" />
                                <x-text-input id="curp" name="curp" type="text" class="mt-1 block w-full uppercase" placeholder="18 caracteres" maxlength="18" />
                            </div>

                            <div class="fisica-only">
                                <x-input-label for="date_of_birth" :value="__('Fecha de Nacimiento')" />
                                <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-1 block w-full text-gray-700 dark:text-gray-300" />
                            </div>

                            <div class="fisica-only md:col-span-2">
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

                    <div class="mb-8 bg-gray-50 dark:bg-gray-900/50 p-6 rounded-lg border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 border-b border-gray-300 dark:border-gray-600 pb-2 mb-4">Perfil Fiscal (Para Facturación)</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="rfc" id="label_rfc" :value="__('RFC (13 caracteres)')" />
                                <x-text-input id="rfc" name="rfc" type="text" class="mt-1 block w-full uppercase" placeholder="Ej. ABCD800101XYZ" maxlength="13" />
                            </div>

                            <div>
                                <x-input-label for="tax_zip_code" :value="__('Código Postal (SAT)')" />
                                <x-text-input id="tax_zip_code" name="tax_zip_code" type="text" class="mt-1 block w-full" placeholder="Ej. 77500" maxlength="5" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="tax_name" :value="__('Razón Social (Exactamente como en la Constancia del SAT)')" />
                                <x-text-input id="tax_name" name="tax_name" type="text" class="mt-1 block w-full uppercase" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="tax_regime" :value="__('Régimen Fiscal')" />
                                <select id="tax_regime" name="tax_regime" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition-colors">
                                    <option value="">-- Seleccionar Régimen --</option>
                                    <option value="601">601 - General de Ley Personas Morales</option>
                                    <option value="605">605 - Sueldos y Salarios e Ingresos Asimilados</option>
                                    <option value="606">606 - Arrendamiento</option>
                                    <option value="612">612 - Personas Físicas con Actividades Empresariales y Profesionales</option>
                                    <option value="626">626 - Régimen Simplificado de Confianza (RESICO)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div id="medical_section" class="mb-8 bg-red-50 dark:bg-red-900/10 p-6 rounded-lg border border-red-100 dark:border-red-900/30">
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
                                <x-input-label for="allergies" :value="__('Alergias')" class="text-red-700 dark:text-red-300" />
                                <x-text-input id="allergies" name="allergies" type="text" class="mt-1 block w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-700 dark:bg-gray-900" placeholder="Ej. Penicilina, Nueces" />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end border-t border-gray-200 dark:border-gray-700 pt-6">
                        <x-primary-button class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-lg">
                            Registrar Cliente
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleClientType() {
            // Revisamos qué opción está seleccionada
            const isFisica = document.querySelector('input[name="client_type"]:checked').value === 'fisica';
            
            // Atrapamos los elementos HTML que vamos a modificar
            const fisicaFields = document.querySelectorAll('.fisica-only');
            const medicalSection = document.getElementById('medical_section');
            const labelName = document.getElementById('label_name');
            const labelRfc = document.getElementById('label_rfc');
            const curpInput = document.getElementById('curp');

            if (isFisica) {
                // Mostrar datos de paciente
                fisicaFields.forEach(el => el.style.display = 'block');
                medicalSection.style.display = 'block';
                labelName.innerText = 'Nombre Completo del Paciente *';
                labelRfc.innerText = 'RFC (13 caracteres)';
                curpInput.disabled = false;
            } else {
                // Ocultar datos médicos y CURP para Empresas
                fisicaFields.forEach(el => el.style.display = 'none');
                medicalSection.style.display = 'none';
                labelName.innerText = 'Nombre Comercial / Representante *';
                labelRfc.innerText = 'RFC de Empresa (12 caracteres)';
                curpInput.value = ''; // Limpiamos el CURP por si habían escrito algo
                curpInput.disabled = true; // Deshabilitamos para que no se envíe a la BD
            }
        }
        
        // Ejecutar la función apenas cargue la página para que inicie en el estado correcto
        document.addEventListener("DOMContentLoaded", toggleClientType);
    </script>
</x-app-layout>