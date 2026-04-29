<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mi Agenda Médica') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm mb-6 border border-gray-200 dark:border-gray-700">
            <a href="{{ route('appointments.index', ['date' => $date->copy()->subDay()->toDateString()]) }}" class="px-4 py-2 bg-gray-50 dark:bg-gray-900 text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition font-medium text-sm">
                &larr; Día Anterior
            </a>
            
            <h3 class="text-xl font-extrabold text-indigo-600 dark:text-indigo-400 capitalize text-center">
                {{ $date->isToday() ? 'Hoy: ' : '' }}{{ $date->translatedFormat('l, j \d\e F Y') }}
            </h3>
            
            <a href="{{ route('appointments.index', ['date' => $date->copy()->addDay()->toDateString()]) }}" class="px-4 py-2 bg-gray-50 dark:bg-gray-900 text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition font-medium text-sm">
                Día Siguiente &rarr;
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700 p-2 sm:p-6">
            @forelse($appointments as $app)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 dark:border-gray-700/50 py-5 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-700/25 transition px-4 rounded-xl">
                    
                    <div class="flex items-center gap-6 mb-4 sm:mb-0">
                        <div class="text-2xl font-black text-indigo-600 dark:text-indigo-400 w-24 tracking-tighter">
                            {{ \Carbon\Carbon::parse($app->start_time)->format('H:i') }}
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white">{{ $app->patient->name }}</h4>
                            <div class="flex items-center mt-1">
                                <span class="relative flex h-2.5 w-2.5 mr-2">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 {{ $app->status === 'Finalizada' ? 'bg-gray-400' : 'bg-green-400' }}"></span>
                                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 {{ $app->status === 'Finalizada' ? 'bg-gray-500' : 'bg-green-500' }}"></span>
                                </span>
                                <span class="text-sm font-medium {{ $app->status === 'Finalizada' ? 'text-gray-500' : 'text-green-600 dark:text-green-400' }}">
                                    {{ $app->status ?? 'Esperando' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div>
                        @if($app->status !== 'Finalizada')
                            <a href="{{ route('consultations.create', $app->id) }}" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                Iniciar Consulta
                            </a>
                        @else
                            <div class="flex items-center gap-2">
                                <span class="inline-flex justify-center items-center px-3 py-3 bg-gray-100 dark:bg-gray-800 text-gray-500 text-xs font-bold rounded-xl border border-gray-200 dark:border-gray-700">
                                    ✔ Finalizada
                                </span>
                                
                                <!-- NUEVO BOTÓN DE RECETA -->
                                <!-- Buscamos la consulta asociada a esta cita para generar el PDF -->
                                @php
                                    $consulta = \App\Models\Consultation::where('appointment_id', $app->id)->first();
                                @endphp
                                
                                @if($consulta)
                                <a href="{{ route('consultations.prescription', $consulta->id) }}" target="_blank" class="inline-flex justify-center items-center px-4 py-3 bg-white dark:bg-gray-800 border border-indigo-200 dark:border-indigo-700 hover:bg-indigo-50 dark:hover:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 text-sm font-bold rounded-xl transition shadow-sm hover:shadow">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    Imprimir Receta
                                </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-16">
                    <div class="w-20 h-20 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Agenda Despejada</h3>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">No tienes pacientes programados para este día.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>