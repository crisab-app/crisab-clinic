<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Tablero de Recepción
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg mb-6 p-4 max-w-sm">
                <form method="GET" action="{{ route('appointments.index') }}" id="agendaFilters">
                    <x-input-label for="date" value="Día a Consultar" />
                    <x-text-input type="date" name="date" id="date" value="{{ $selectedDate }}" onchange="this.form.submit()" class="mt-1 block w-full" />
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider sticky left-0 z-10 w-24">
                                    HORA
                                </th>
                                @foreach($doctors as $doctor)
                                    <th class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 text-center text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider border-l border-gray-200 dark:border-gray-700">
                                        Dr. {{ $doctor->name }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($timeSlots as $time => $doctorSlots)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-gray-100 sticky left-0 bg-white dark:bg-gray-800 z-10 border-r border-gray-200 dark:border-gray-700">
                                        {{ $time }}
                                    </td>

                                    @foreach($doctors as $doctor)
                                        @php $appointment = $doctorSlots[$doctor->id]; @endphp
                                        
                                        <td class="p-2 text-center border-l border-gray-200 dark:border-gray-700 relative align-top min-w-[200px]">
                                            @if($appointment)
                                                <div class="p-3 bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 rounded shadow-sm text-left border-l-4 border-red-500">
                                                    <span class="text-xs font-bold uppercase tracking-wider">Ocupado</span>
                                                    <p class="text-sm font-medium mt-1 truncate" title="{{ $appointment->patient->name }}">
                                                        👤 {{ $appointment->patient->name }}
                                                    </p>
                                                </div>
                                            @else
                                                <a href="{{ route('appointments.create', ['doctor_id' => $doctor->id, 'date' => $selectedDate, 'time' => $time]) }}" 
                                                   class="absolute inset-2 flex items-center justify-center border-2 border-dashed border-transparent hover:border-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 rounded transition-all group">
                                                    <span class="text-green-600 dark:text-green-400 font-bold opacity-0 group-hover:opacity-100 flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                        Agendar Aquí
                                                    </span>
                                                </a>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>