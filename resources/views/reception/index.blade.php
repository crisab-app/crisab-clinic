<x-app-layout>
    <div x-data="{ showModal: false }">
        
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Centro de Mando: Recepción') }}
                </h2>
                <button @click="showModal = true" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-md shadow-lg transition-transform transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Cita Rápida
                </button>
            </div>
        </x-slot>

        <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-4 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 p-4 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-between items-center bg-white dark:bg-gray-800 p-4 rounded-t-lg border-b border-gray-200 dark:border-gray-700">
                <a href="{{ route('reception.index', ['date' => $date->copy()->subMonth()->toDateString()]) }}" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                    &larr; Mes Anterior
                </a>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white capitalize">
                    {{ $date->translatedFormat('F Y') }}
                </h3>
                <a href="{{ route('reception.index', ['date' => $date->copy()->addMonth()->toDateString()]) }}" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                    Mes Siguiente &rarr;
                </a>
            </div>

            <div class="grid grid-cols-7 gap-px bg-gray-200 dark:bg-gray-700">
                @foreach(['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] as $day)
                    <div class="bg-gray-50 dark:bg-gray-900 p-2 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        {{ $day }}
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-7 gap-px bg-gray-200 dark:bg-gray-700 rounded-b-lg overflow-hidden shadow-sm">
                @foreach($days as $dayInfo)
                    <div class="min-h-[120px] p-2 transition-colors {{ $dayInfo['isCurrentMonth'] ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-900/50' }} {{ $dayInfo['isToday'] ? 'ring-2 ring-inset ring-indigo-500' : '' }}">
                        <div class="flex justify-between items-start">
                            <span class="text-sm font-semibold {{ $dayInfo['isToday'] ? 'text-indigo-600 dark:text-indigo-400' : ($dayInfo['isCurrentMonth'] ? 'text-gray-900 dark:text-gray-100' : 'text-gray-400 dark:text-gray-600') }}">
                                {{ $dayInfo['date']->format('j') }}
                            </span>
                            @if(count($dayInfo['appointments']) > 0)
                                <span class="bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200 text-xs font-bold px-2 py-0.5 rounded-full">
                                    {{ count($dayInfo['appointments']) }}
                                </span>
                            @endif
                        </div>
                        
                        <div class="mt-2 space-y-1">
                            @foreach($dayInfo['appointments']->take(3) as $appointment)
                                <div class="text-[10px] truncate p-1 rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-l-2 border-indigo-500" title="{{ $appointment->patient->name }} con {{ $appointment->user->name }}">
                                    {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ $appointment->patient->name }}
                                </div>
                            @endforeach
                            @if(count($dayInfo['appointments']) > 3)
                                <div class="text-[10px] text-gray-500 dark:text-gray-400 text-center font-medium">
                                    + {{ count($dayInfo['appointments']) - 3 }} más
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80 transition-opacity" @click="showModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showModal" x-transition.scale class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-200 dark:border-gray-700">
                    <form action="{{ route('appointments.store') }}" method="POST">
                        @csrf
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4" id="modal-title">Agendar Nueva Cita</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="patient_id" value="Paciente" />
                                    <select name="patient_id" id="patient_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md" required>
                                        <option value="">Seleccione un paciente</option>
                                        @foreach($patients as $patient)
                                            <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <x-input-label for="user_id" value="Médico Asignado" />
                                    <select name="user_id" id="user_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md" required>
                                        <option value="">Seleccione el médico</option>
                                        @foreach($doctors as $doctor)
                                            <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <x-input-label for="resource_id" value="Consultorio / Área" />
                                    <select name="resource_id" id="resource_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md" required>
                                        <option value="">Seleccione el espacio</option>
                                        @foreach($resources as $resource)
                                            <option value="{{ $resource->id }}">{{ $resource->name ?? 'Consultorio' }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="start_time" value="Fecha y Hora de Inicio" />
                                        <input type="datetime-local" name="start_time" id="start_time" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md" required>
                                    </div>
                                    <div>
                                        <x-input-label for="end_time" value="Fecha y Hora de Fin" />
                                        <input type="datetime-local" name="end_time" id="end_time" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Agendar
                            </button>
                            <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>