<x-guest-layout>
    <div class="max-w-md mx-auto" x-data="{ step: 1 }">
        
        <div class="text-center mb-8">
            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white">Bienvenido(a) a la Clínica</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Ayúdanos a preparar tu expediente médico para tu próxima cita.</p>
        </div>

        <form action="{{ route('onboarding.store', $token) }}" method="POST" class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
            @csrf

            <div x-show="step === 1" x-transition.opacity>
                <div class="mb-6 flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-2">
                    <h3 class="text-lg font-bold text-indigo-600 dark:text-indigo-400">1. Identidad</h3>
                    <span class="text-xs font-bold bg-indigo-100 text-indigo-800 py-1 px-2 rounded-full">Paso 1 de 2</span>
                </div>

                <div class="space-y-5">
                    <div>
                        <x-input-label for="name" value="Nombre Completo" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full text-lg py-3" value="{{ $patient->name }}" required />
                    </div>

                    <div>
                        <x-input-label for="curp" value="CURP (Opcional)" />
                        <x-text-input id="curp" name="curp" type="text" class="mt-1 block w-full uppercase py-3" maxlength="18" placeholder="Ingresa tus 18 caracteres" oninput="decodeCURP(this.value)" />
                        <p class="text-xs text-indigo-500 mt-1">Si ingresas tu CURP, calcularemos tu edad automáticamente.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="date_of_birth" value="Nacimiento" />
                            <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-1 block w-full text-gray-600" />
                        </div>
                        <div>
                            <x-input-label for="gender" value="Género" />
                            <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm h-[42px]">
                                <option value="">Selecciona</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <button type="button" @click="step = 2" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                        Siguiente Paso &rarr;
                    </button>
                </div>
            </div>

            <div x-show="step === 2" x-transition.opacity style="display: none;">
                <div class="mb-6 flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-2">
                    <h3 class="text-lg font-bold text-red-600 dark:text-red-400">2. Médico y Emergencia</h3>
                    <span class="text-xs font-bold bg-indigo-100 text-indigo-800 py-1 px-2 rounded-full">Paso 2 de 2</span>
                </div>

                <div class="space-y-5">
                    <div class="bg-red-50 dark:bg-red-900/10 p-4 rounded-xl border border-red-100 dark:border-red-900/30 mb-4">
                        <div class="mb-4">
                            <x-input-label for="emergency_contact_name" value="Nombre de Contacto de Emergencia" class="text-red-700" />
                            <x-text-input id="emergency_contact_name" name="emergency_contact_name" type="text" class="mt-1 block w-full" placeholder="Ej. María Pérez (Madre)" />
                        </div>
                        <div>
                            <x-input-label for="emergency_contact_phone" value="Teléfono de Emergencia" class="text-red-700" />
                            <x-text-input id="emergency_contact_phone" name="emergency_contact_phone" type="tel" class="mt-1 block w-full" placeholder="10 dígitos" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <x-input-label for="allergies" value="Alergias (Importante)" />
                            <x-text-input id="allergies" name="allergies" type="text" class="mt-1 block w-full border-red-300" placeholder="Medicamentos, alimentos..." />
                        </div>
                        <div class="col-span-2">
                            <x-input-label for="blood_type" value="Tipo de Sangre" />
                            <select id="blood_type" name="blood_type" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md">
                                <option value="">No lo sé</option>
                                <option value="O+">O Positivo (O+)</option>
                                <option value="O-">O Negativo (O-)</option>
                                <option value="A+">A Positivo (A+)</option>
                                <option value="A-">A Negativo (A-)</option>
                                <option value="B+">B Positivo (B+)</option>
                                <option value="B-">B Negativo (B-)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <button type="button" @click="step = 1" class="w-1/3 flex justify-center py-3 px-4 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm text-sm font-bold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 transition-all">
                        &larr; Volver
                    </button>
                    <button type="submit" class="w-2/3 flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-green-600 hover:bg-green-700 transition-all">
                        Finalizar y Enviar
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function decodeCURP(curp) {
            curp = curp.toUpperCase();
            if (curp.length !== 18) return; // Espera a que termine de escribir

            // Extraer la fecha de nacimiento (Posiciones 4 a 9)
            let year = curp.substring(4, 6);
            let month = curp.substring(6, 8);
            let day = curp.substring(8, 10);

            // Extraer el Género (Posición 10)
            let genderChar = curp.charAt(10);
            let gender = '';
            if (genderChar === 'H') gender = 'Masculino';
            else if (genderChar === 'M') gender = 'Femenino';

            // El truco matemático de México: Calcular el Siglo
            // Antes del año 2000 el penúltimo dígito era número (0-9). 
            // Del 2000 en adelante, es letra (A-Z).
            let sigloChar = curp.charAt(16);
            let fullYear = "";
            if (sigloChar >= '0' && sigloChar <= '9') {
                fullYear = "19" + year; // Nació antes del 2000
            } else {
                fullYear = "20" + year; // Nació después del 2000
            }

            let dob = `${fullYear}-${month}-${day}`;

            // Auto-completar visualmente en el formulario
            if (!isNaN(Date.parse(dob))) {
                document.getElementById('date_of_birth').value = dob;
                // Efecto visual para mostrar que fue automático
                document.getElementById('date_of_birth').classList.add('bg-green-50', 'border-green-400');
            }
            if (gender) {
                document.getElementById('gender').value = gender;
                document.getElementById('gender').classList.add('bg-green-50', 'border-green-400');
            }
        }
    </script>
</x-guest-layout>