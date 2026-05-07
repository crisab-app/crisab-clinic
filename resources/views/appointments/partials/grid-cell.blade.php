@if($appointment)
    @php
        // Si vemos por recursos, el color lo da el médico. Si vemos por médicos, el color lo da el recurso.
        $color = ($viewBy === 'resources') ? ($appointment->doctor->color ?? '#ef4444') : ($appointment->resource->color ?? '#ef4444');
    @endphp
    
    <a href="{{ route('appointments.edit', $appointment->id) }}" 
       class="block h-full w-full p-2 rounded shadow-sm text-left border-l-4 overflow-hidden hover:opacity-75 transition-opacity cursor-pointer" 
       style="background-color: {{ $color }}20; border-color: {{ $color }}; color: {{ $color }};">
        
        <span class="text-[10px] font-bold uppercase tracking-wider block mb-1">
            {{ $viewBy === 'resources' ? 'Dr. ' . $appointment->doctor->name : $appointment->resource->name }}
        </span>
        <p class="text-xs font-bold truncate" title="{{ $appointment->patient->name }}">
            👤 {{ $appointment->patient->name }}
        </p>
    </a>
@else
    <a href="{{ route('appointments.create', ['doctor_id' => $viewBy === 'doctors' ? $header->id : null, 'resource_id' => $viewBy === 'resources' ? $header->id : null, 'date' => $selectedDate, 'time' => $time]) }}" 
       class="absolute inset-1 flex items-center justify-center border-2 border-dashed border-transparent hover:border-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 rounded transition-all group cursor-pointer">
        <span class="text-green-600 dark:text-green-400 text-xs font-bold opacity-0 group-hover:opacity-100 flex items-center">
            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Agendar
        </span>
    </a>
@endif