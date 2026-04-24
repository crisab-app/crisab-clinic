<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Consulta Médica') }}
            </h2>
            <a href="{{ route('appointments.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                &larr; Volver a la Agenda
            </a>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="col-span-1 space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-bold text-xl">
                            {{ substr($patient->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white leading-tight">{{ $patient->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                @if($patient->date_of_birth)
                                    {{ \Carbon\Carbon::parse($patient->date_of_birth)->age }} años &bull; {{ $patient->gender ?? 'N/E' }}
                                @else
                                    Edad no registrada
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="space-y-3 mt-6">
                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 p-3 rounded-lg">
                            <span class="text-xs font-bold text-red-600 dark:text-red-400 uppercase tracking-wider block mb-1">Alergias</span>
                            <span class="text-sm text-red-800 dark:text-red-200">{{ $patient->allergies ?: 'Ninguna registrada / Desconoce' }}</span>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-gray-50 dark:bg-gray-900/50 p-3 rounded-lg border border-gray-100 dark:border-gray-700">
                                <span class="text-xs font-bold text-gray-500 uppercase block mb-1">Tipo de Sangre</span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $patient->blood_type ?: 'N/E' }}</span>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-900/50 p-3 rounded-lg border border-gray-100 dark:border-gray-700">
                                <span class="text-xs font-bold text-gray-500 uppercase block mb-1">Teléfono</span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $patient->phone ?: 'N/E' }}</span>
                            </div>
                        </div>

                        @if($patient->emergency_contact_name)
                        <div class="bg-orange-50 dark:bg-orange-900/20 p-3 rounded-lg border border-orange-100 dark:border-orange-800 mt-2">
                            <span class="text-xs font-bold text-orange-600 dark:text-orange-400 uppercase tracking-wider block mb-1">Contacto de Emergencia</span>
                            <span class="text-sm text-orange-800 dark:text-orange-200 block">{{ $patient->emergency_contact_name }}</span>
                            <span class="text-sm text-orange-800 dark:text-orange-200 font-medium">{{ $patient->emergency_contact_phone }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-span-1 md:col-span-2">
                <form action="{{ route('consultations.store', $appointment->id) }}" method="POST" x-data="{ tab: 'notas' }">
                    @csrf
                    
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                        
                        <div class="flex border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                            <button type="button" @click="tab = 'notas'" :class="{ 'border-b-2 border-indigo-500 text-indigo-600 dark:text-indigo-400 bg-white dark:bg-gray-800': tab === 'notas', 'text-gray-500 hover:text-gray-700 dark:text-gray-400': tab !== 'notas' }" class="flex-1 py-4 px-6 text-sm font-bold text-center transition-colors">
                                Notas Clínicas (SOEP)
                            </button>
                            <button type="button" @click="tab = 'signos'" :class="{ 'border-b-2 border-indigo-500 text-indigo-600 dark:text-indigo-400 bg-white dark:bg-gray-800': tab === 'signos', 'text-gray-500 hover:text-gray-700 dark:text-gray-400': tab !== 'signos' }" class="flex-1 py-4 px-6 text-sm font-bold text-center transition-colors">
                                Signos Vitales
                            </button>
                        </div>

                        <div class="p-6">
                            
                            <div x-show="tab === 'notas'" x-transition.opacity class="space-y-6">
                                <div>
                                    <x-input-label for="subjective" value="Motivo de Consulta / Síntomas (Subjetivo)" />
                                    <textarea name="subjective" id="subjective" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm" placeholder="¿Qué refiere el paciente?"></textarea>
                                </div>
                                
                                <div>
                                    <x-input-label for="objective" value="Exploración Física (Objetivo)" />
                                    <textarea name="objective" id="objective" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm" placeholder="Hallazgos de la revisión médica"></textarea>
                                </div>

                                <div>
                                    <x-input-label for="assessment" value="Diagnóstico (Análisis)" class="text-indigo-600 dark:text-indigo-400" />
                                    <textarea name="assessment" id="assessment" rows="2" class="mt-1 block w-full border-indigo-300 dark:border-indigo-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm" required placeholder="Diagnóstico principal"></textarea>
                                </div>

                                <div>
                                    <x-input-label for="plan" value="Plan y Receta Médica" class="text-emerald-600 dark:text-emerald-400" />
                                    <textarea name="plan" id="plan" rows="5" class="mt-1 block w-full border-emerald-300 dark:border-emerald-700 dark:bg-gray-900 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm" placeholder="1. Paracetamol 500mg cada 8 horas por 3 días..."></textarea>
                                    <p class="text-xs text-gray-500 mt-2">Este texto aparecerá impreso en la receta médica.</p>
                                </div>
                            </div>

                            <div x-show="tab === 'signos'" style="display: none;" x-transition.opacity>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                    <div>
                                        <x-input-label for="weight" value="Peso (kg)" />
                                        <x-text-input id="weight" name="vitals[weight]" type="number" step="0.1" class="mt-1 block w-full" placeholder="Ej. 75.5" />
                                    </div>
                                    <div>
                                        <x-input-label for="height" value="Estatura (cm)" />
                                        <x-text-input id="height" name="vitals[height]" type="number" step="1" class="mt-1 block w-full" placeholder="Ej. 175" />
                                    </div>
                                    <div>
                                        <x-input-label for="temp" value="Temp. (°C)" />
                                        <x-text-input id="temp" name="vitals[temp]" type="number" step="0.1" class="mt-1 block w-full" placeholder="Ej. 36.5" />
                                    </div>
                                    <div>
                                        <x-input-label for="hr" value="Frec. Card. (lpm)" />
                                        <x-text-input id="hr" name="vitals[hr]" type="number" class="mt-1 block w-full" placeholder="Ej. 80" />
                                    </div>
                                    <div class="col-span-2">
                                        <x-input-label for="bp" value="Presión Arterial (Ej. 120/80)" />
                                        <x-text-input id="bp" name="vitals[bp]" type="text" class="mt-1 block w-full" placeholder="120/80" />
                                    </div>
                                </div>
                            </div>

                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-900/50 p-6 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-emerald-600 border border-transparent rounded-xl font-bold text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 shadow-lg transform transition hover:-translate-y-0.5">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Guardar Consulta y Finalizar
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>