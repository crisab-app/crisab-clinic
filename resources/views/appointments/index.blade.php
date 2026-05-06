<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Agenda Médica
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg mb-6 p-4">
                <form method="GET" action="{{ route('agenda.index') }}" class="flex flex-col sm:flex-row gap-4 items-end" id="agendaFilters">
                    
                    <div class="flex-1 w-full">
                        <x-input-label for="doctor_id" value="Seleccionar Doctor" />
                        <select name="doctor_id" id="doctor_id" onchange="this.form.submit()" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ $selectedDoctorId == $doctor->id ? 'selected' : '' }}>
                                    Dr. {{ $doctor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex-1 w-full">
                        <x-input-label for="date" value="Ir a la Fecha" />
                        <x-text-input type="date" name="date" id="date" value="{{ $selectedDate }}" onchange="this.form.submit()" class="mt-1 block w-full" />
                    </div>

                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300 mb-4">
                    Horarios para el {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l j \d\e F, Y') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($timeSlots as $time => $appointment)
                        @if($appointment)
                            <div class="p-4 border border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-800 rounded-lg shadow-sm flex justify-between items-center opacity-75">
                                <div>
                                    <span class="font-bold text-red-700 dark:text-red-400">{{ $time }}</span>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $appointment->patient->name }}</p>
                                </div>
                                <span class="text-xs font-semibold px-2 py-1 bg-red-100 text-red-800 rounded-full dark:bg-red-900 dark:text-red-300">
                                    Ocupado
                                </span>
                            </div>
                        @else
                            <a href="{{ route('appointments.create', ['doctor_id' => $selectedDoctorId, 'date' => $selectedDate, 'time' => $time]) }}" 
                               class="p-4 border border-green-300 bg-white dark:bg-gray-800 dark:border-green-700 rounded-lg shadow-sm hover:shadow-md hover:border-green-500 hover:bg-green-50 dark:hover:bg-green-900/30 transition-all cursor-pointer group flex justify-between items-center">
                                
                                <span class="font-bold text-gray-700 dark:text-gray-300 group-hover:text-green-600 dark:group-hover:text-green-400">
                                    {{ $time }}
                                </span>
                                
                                <span class="text-sm text-green-600 dark:text-green-400 opacity-0 group-hover:opacity-100 transition-opacity">
                                    + Agendar aquí
                                </span>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>