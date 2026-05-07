<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center" x-data>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Centro de Mando: Recepción') }}
            </h2>
            <button @click.prevent="$dispatch('abrir-modal')" type="button" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-md shadow-lg transition-transform transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Cita Rápida
            </button>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8" x-data>
        
        @if (session('success'))
            <div class="mb-4 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 p-4 rounded-lg shadow-sm border border-green-200 dark:border-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 p-4 rounded-lg shadow-sm border border-red-200 dark:border-red-800">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex justify-between items-center bg-white dark:bg-gray-800 p-4 rounded-t-lg border-b border-gray-200 dark:border-gray-700 shadow-sm">
            <a href="{{ route('reception.index', ['date' => $date->copy()->subMonth()->toDateString()]) }}" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors">
                &larr; Mes Anterior
            </a>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white capitalize">
                {{ $date->translatedFormat('F Y') }}
            </h3>
            <a href="{{ route('reception.index', ['date' => $date->copy()->addMonth()->toDateString()]) }}" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors">
                Mes Siguiente &rarr;
            </a>
        </div>

        <div class="grid grid-cols-7 gap-px bg-gray-200 dark:bg-gray-700 shadow-sm">
            @foreach(['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] as $day)
                <div class="bg-gray-50 dark:bg-gray-900 p-2 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                    {{ $day }}
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-7 gap-px bg-gray-200 dark:bg-gray-700 rounded-b-lg overflow-hidden shadow-sm">
            @foreach($days as $dayInfo)
                <div class="min-h-[120px] p-2 transition-colors {{ $dayInfo['isCurrentMonth'] ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-900/50' }} {{ $dayInfo['isToday'] ? 'ring-2 ring-inset ring-indigo-500' : '' }}">
                    
                    <div class="flex justify-between items-start mb-2">
                        <a href="{{ route('appointments.index', ['date' => $dayInfo['date']->format('Y-m-d')]) }}" 
                           title="Ver agenda completa de este día"
                           class="text-sm font-bold hover:underline hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors {{ $dayInfo['isToday'] ? 'text-indigo-600 dark:text-indigo-400' : ($dayInfo['isCurrentMonth'] ? 'text-gray-900 dark:text-gray-100' : 'text-gray-400 dark:text-gray-600') }}">
                            {{ $dayInfo['date']->format('j') }}
                        </a>
                        
                        @if(count($dayInfo['appointments']) > 0)
                            <span class="bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-200 text-xs font-bold px-2 py-0.5 rounded-full shadow-sm">
                                {{ count($dayInfo['appointments']) }}
                            </span>
                        @endif
                    </div>
                    
                    <div class="space-y-1.5">
                        @foreach($dayInfo['appointments']->take(3) as $appointment)
                            <div class="flex items-center justify-between p-1.5 rounded bg-gray-50 dark:bg-gray-700/50 border-l-2 border-indigo-500 group shadow-sm hover:shadow transition-shadow" 
                                 title="Paciente: {{ $appointment->patient->name }} | Dr. {{ $appointment->doctor->name }}">
                                
                                <a href="{{ route('appointments.edit', $appointment->id) }}" class="text-[11px] text-gray-700 dark:text-gray-300 truncate hover:text-indigo-600 dark:hover:text-indigo-400 text-left w-full cursor-pointer transition-colors block font-medium">
                                    {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ $appointment->patient->name }}
                                </a>
                                
                                <a href="{{ route('reception.whatsapp', $appointment->patient_id) }}" target="_blank" class="text-green-500 hover:text-green-600 opacity-0 group-hover:opacity-100 transition-opacity ml-1 flex-shrink-0" title="Enviar mensaje de WhatsApp">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                </a>
                            </div>
                        @endforeach
                        
                        @if(count($dayInfo['appointments']) > 3)
                            <div class="text-[10px] text-gray-500 dark:text-gray-400 text-center font-bold mt-1">
                                + {{ count($dayInfo['appointments']) - 3 }} más
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div x-data="{ showModal: false }" @abrir-modal.window="showModal = true" x-cloak x-show="showModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80 transition-opacity" @click="showModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showModal" x-transition.scale class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-200 dark:border-gray-700">
                <form action="{{ route('appointments.store') }}" method="POST">
                    @csrf
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white mb-4">Agendar Nueva Cita</h3>
                        <div class="space-y-4">
                            <div>
                                <x-input-label for="patient_id" value="Paciente" />
                                <select name="patient_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md" required>
                                    <option value="">Seleccione un paciente</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="user_id" value="Médico Asignado" />
                                <select name="user_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md" required>
                                    <option value="">Seleccione el médico</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="resource_id" value="Consultorio / Área" />
                                <select name="resource_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md" required>
                                    <option value="">Seleccione el espacio</option>
                                    @foreach($resources as $resource)
                                        <option value="{{ $resource->id }}">{{ $resource->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="start_time" value="Fecha y Hora de Inicio" />
                                    <input type="datetime-local" name="start_time" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                </div>
                                <div>
                                    <x-input-label for="end_time" value="Fecha y Hora de Fin" />
                                    <input type="datetime-local" name="end_time" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t dark:border-gray-600">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-bold text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm transition-colors">Guardar Cita</button>
                        <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>