<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                Consulta Médica: <span class="text-indigo-600 dark:text-indigo-400">{{ $patient->name }}</span>
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                Fecha: {{ now()->translatedFormat('d \d\e F, Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <form action="{{ route('consultations.store', $appointment->id) }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="space-y-6 lg:col-span-1">
                    
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-5 border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            Evolución del Paciente
                        </h3>
                        
                        @php
                            // Jalamos las últimas 3 consultas de este paciente
                            $pastConsultations = \App\Models\Consultation::where('patient_id', $patient->id)->latest()->take(3)->get();
                        @endphp

                        @if($pastConsultations->count() > 0)
                            <div class="space-y-4">
                                @foreach($pastConsultations as $past)
                                    <div class="text-sm border-l-2 border-indigo-500 pl-3">
                                        <div class="font-bold text-gray-700 dark:text-gray-300">{{ $past->created_at->format('d/m/Y') }}</div>
                                        <div class="text-gray-500 dark:text-gray-400">
                                            Peso: {{ $past->vitals['weight'] ?? '--' }} kg | PA: {{ $past->vitals['blood_pressure'] ?? '--' }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 italic">Primera consulta registrada en el sistema.</p>
                        @endif
                    </div>

                    <div class="bg-indigo-50 dark:bg-indigo-900/20 shadow-sm rounded-lg p-5 border border-indigo-100 dark:border-indigo-800">
                        <h3 class="text-lg font-bold text-indigo-900 dark:text-indigo-300 mb-4">Signos Vitales de Hoy</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label value="Peso (kg)" />
                                <x-text-input name="vitals[weight]" type="number" step="0.1" class="w-full mt-1" placeholder="Ej. 75.5" />
                            </div>
                            <div>
                                <x-input-label value="Talla (cm)" />
                                <x-text-input name="vitals[height]" type="number" class="w-full mt-1" placeholder="Ej. 170" />
                            </div>
                            <div>
                                <x-input-label value="Presión Arterial" />
                                <x-text-input name="vitals[blood_pressure]" type="text" class="w-full mt-1" placeholder="Ej. 120/80" />
                            </div>
                            <div>
                                <x-input-label value="Temp. (°C)" />
                                <x-text-input name="vitals[temperature]" type="number" step="0.1" class="w-full mt-1" placeholder="Ej. 36.5" />
                            </div>
                            <div class="col-span-2">
                                <x-input-label value="Frecuencia Cardíaca (lpm)" />
                                <x-text-input name="vitals[heart_rate]" type="number" class="w-full mt-1" placeholder="Ej. 72" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6 lg:col-span-2">
                    
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-5 border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Notas Clínicas</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <x-input-label for="subjective" value="Motivo de Consulta (Subjetivo)" />
                                <textarea name="subjective" rows="2" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Lo que el paciente refiere..."></textarea>
                            </div>
                            <div>
                                <x-input-label for="objective" value="Exploración Física (Objetivo)" />
                                <textarea name="objective" rows="2" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Hallazgos en la revisión médica..."></textarea>
                            </div>
                            <div>
                                <x-input-label for="assessment" value="Diagnóstico (Análisis)" />
                                <textarea name="assessment" rows="2" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm border-l-4 border-l-indigo-500" placeholder="Diagnóstico principal..." required></textarea>
                            </div>
                            <div>
                                <x-input-label for="plan" value="Plan de Tratamiento (No farmacológico)" />
                                <textarea name="plan" rows="2" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Reposo, dieta, estudios de laboratorio..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-5 border border-gray-200 dark:border-gray-700" 
                         x-data="{ 
                            prescriptions: [],
                            addPrescription() {
                                this.prescriptions.push({ medication_id: '', dosage: '', quantity: 1 });
                            },
                            removePrescription(index) {
                                this.prescriptions.splice(index, 1);
                            }
                         }">
                        
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Receta Médica</h3>
                            <button type="button" @click="addPrescription()" class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 hover:bg-green-200 rounded-md font-bold text-sm transition-colors">
                                + Agregar Medicamento
                            </button>
                        </div>

                        <div x-show="prescriptions.length === 0" class="text-center py-6 bg-gray-50 dark:bg-gray-900/50 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-700">
                            <p class="text-gray-500 dark:text-gray-400 text-sm">No se han agregado medicamentos a la receta.</p>
                        </div>

                        <div class="space-y-4">
                            <template x-for="(item, index) in prescriptions" :key="index">
                                <div class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700 relative group">
                                    
                                    <div class="flex-grow grid grid-cols-12 gap-3">
                                        <div class="col-span-12 md:col-span-5">
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Medicamento</label>
                                            <select x-model="item.medication_id" :name="`prescriptions[${index}][medication_id]`" class="mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                                <option value="">Selecciona del Vademécum...</option>
                                                @foreach($medications as $med)
                                                    <option value="{{ $med->id }}">{{ $med->name }} {{ $med->presentation ? ' - '.$med->presentation : '' }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-span-12 md:col-span-5">
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Dosis e Indicaciones</label>
                                            <input type="text" x-model="item.dosage" :name="`prescriptions[${index}][dosage]`" class="mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm rounded-md shadow-sm" placeholder="Ej. Tomar 1 tableta cada 8 horas" required>
                                        </div>

                                        <div class="col-span-12 md:col-span-2">
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Cajas</label>
                                            <input type="number" x-model="item.quantity" :name="`prescriptions[${index}][quantity]`" min="1" class="mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm rounded-md shadow-sm text-center" required>
                                        </div>
                                    </div>

                                    <button type="button" @click="removePrescription(index)" class="mt-6 text-red-500 hover:text-red-700 transition-colors" title="Quitar medicamento">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                </div>
            </div>

            <div class="mt-8 bg-gray-50 dark:bg-gray-800/50 p-4 rounded-lg flex justify-end border-t border-gray-200 dark:border-gray-700 shadow-sm">
                <a href="{{ route('reception.index') }}" class="mr-4 inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-md shadow-lg transition-transform transform hover:scale-105">
                    Terminar Consulta y Guardar Receta
                </button>
            </div>
        </form>
    </div>
</x-app-layout>