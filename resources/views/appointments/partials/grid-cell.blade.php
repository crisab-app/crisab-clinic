@if($appointment)
    <div class="h-full w-full p-2 bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 rounded shadow-sm text-left border-l-4 border-red-500 overflow-hidden">
        <span class="text-[10px] font-bold uppercase tracking-wider block mb-1">Ocupado</span>
        <p class="text-xs font-medium truncate" title="{{ $appointment->patient->name }}">
            👤 {{ $appointment->patient->name }}
        </p>
    </div>
@else
    <a href="{{ route('appointments.create', ['doctor_id' => $doctor->id, 'date' => $selectedDate, 'time' => $time]) }}" 
       class="absolute inset-1 flex items-center justify-center border-2 border-dashed border-transparent hover:border-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 rounded transition-all group cursor-pointer">
        <span class="text-green-600 dark:text-green-400 text-xs font-bold opacity-0 group-hover:opacity-100 flex items-center">
            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Agendar
        </span>
    </a>
@endif