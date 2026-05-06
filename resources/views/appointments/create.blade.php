<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Nueva Cita Médica
            </h2>
            <a href="{{ route('appointments.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md text-sm transition-colors">
                &larr; Volver a la Agenda
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                
                <form action="{{ route('appointments.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <input type="hidden" name="user_id" value="{{ $doctor_id }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="space-y-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b pb-2 dark:border-gray-700">Horario</h3>
                            
                            <div>
                                <x-input-label for="start_time" value="Inicio de la Cita" />
                                <x-text-input id="start_time" name="start_time" type="datetime-local" class="mt-1 block w-full" :value="old('start_time', $start_time)" required />
                                <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="end_time" value="Fin de la Cita (Aprox.)" />
                                <x-text-input id="end_time" name="end_time" type="datetime-local" class="mt-1 block w-full" :value="old('end_time')" required />
                                <p class="text-xs text-gray-500 mt-1">Por lo general, suma 30 o 60 minutos a la hora de inicio.</p>
                                <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                            </div>
                        </div>

                        <div class="space-y-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b pb-2 dark:border-gray-700">Asignación</h3>

                            <div>
                                <x-input-label for="patient_id" value="Seleccionar Paciente" />
                                <x-text-input id="patient_id" name="patient_id" type="number" placeholder="ID del paciente" class="mt-1 block w-full" :value="old('patient_id')" required />
                                <x-input-error :messages="$errors->get('patient_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="resource_id" value="Consultorio / Recurso (Opcional)" />
                                <x-text-input id="resource_id" name="resource_id" type="number" placeholder="ID del consultorio" class="mt-1 block w-full" :value="old('resource_id')" />
                                <x-input-error :messages="$errors->get('resource_id')" class="mt-2" />
                            </div>
                        </div>

                    </div>

                    <div class="flex justify-end pt-6 border-t border-gray-200 dark:border-gray-700 mt-6">
                        <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-sm transition-colors">
                            Guardar Cita
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>