<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Agenda Médica Diaria') }}
            </h2>
            <a href="{{ route('appointments.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 shadow-sm transition">
                + Agendar Cita
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 flex justify-between items-center bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                <a href="{{ route('appointments.index', ['date' => $date->copy()->subDay()->toDateString()]) }}" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition">
                    &larr; Día Anterior
                </a>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white capitalize">
                    {{ $date->translatedFormat('l, d \d\e F Y') }}
                </h3>
                <a href="{{ route('appointments.index', ['date' => $date->copy()->addDay()->toDateString()]) }}" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition">
                    Día Siguiente &rarr;
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <div style="min-width: 800px;">
                        
                        <div class="grid bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700" 
                             style="grid-template-columns: 80px repeat({{ max(1, count($doctors)) }}, minmax(0, 1fr));">
                            <div class="p-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Hora</div>
                            @forelse($doctors as $doctor)
                                <div class="p-4 text-center border-l border-gray-200 dark:border-gray-700">
                                    <div class="font-bold text-gray-900 dark:text-gray-100">{{ $doctor->name }}</div>
                                    <div class="text-xs text-indigo-500">{{ $doctor->specialty ?? 'Médico General' }}</div>
                                </div>
                            @empty
                                <div class="p-4 text-center border-l border-gray-200 dark:border-gray-700 text-gray-500 text-sm">
                                    No hay médicos registrados aún.
                                </div>
                            @endforelse
                        </div>

                        <div class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                            @foreach($hours as $hour)
                                <div class="grid hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors group" 
                                     style="grid-template-columns: 80px repeat({{ max(1, count($doctors)) }}, minmax(0, 1fr)); min-height: 100px;">
                                    
                                    <div class="p-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400">
                                        {{ $hour }}
                                    </div>

                                    @forelse($doctors as $doctor)
                                        <div class="border-l border-gray-200 dark:border-gray-700 p-2 relative group-hover:bg-gray-50 dark:group-hover:bg-gray-800/80 cursor-pointer">
                                            
                                            @php
                                                $appointment = $appointments->first(function($app) use ($doctor, $hour) {
                                                    return $app->user_id === $doctor->id && 
                                                           \Carbon\Carbon::parse($app->start_time)->format('H:00') === $hour;
                                                });
                                            @endphp

                                            @if($appointment)
                                                <div class="absolute inset-x-1 top-1 bottom-1 bg-indigo-100 dark:bg-indigo-900/40 border-l-4 border-indigo-500 rounded p-2 shadow-sm z-10 overflow-hidden">
                                                    <div class="text-xs font-bold text-indigo-800 dark:text-indigo-300 truncate">
                                                        {{ $appointment->patient->name }}
                                                    </div>
                                                    <div class="text-[10px] text-indigo-600 dark:text-indigo-400 mt-1">
                                                        {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}
                                                    </div>
                                                </div>
                                            @else
                                                <div class="hidden group-hover:flex w-full h-full items-center justify-center">
                                                    <span class="text-2xl text-gray-300 dark:text-gray-600 font-light">+</span>
                                                </div>
                                            @endif
                                        </div>
                                    @empty
                                        <div class="border-l border-gray-200 dark:border-gray-700 p-2"></div>
                                    @endforelse
                                    
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>