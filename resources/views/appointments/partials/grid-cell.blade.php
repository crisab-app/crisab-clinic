@if($appointment)
    @php
        // Si vemos por recursos, el color lo da el médico. Si vemos por médicos, el color lo da el recurso.
        $color = ($viewBy === 'resources') ? ($appointment->doctor->color ?? '#ef4444') : ($appointment->resource->color ?? '#ef4444');
    @endphp
    
    <div class="h-full w-full p-2 rounded shadow-sm text-left border-l-4 overflow-hidden" 
         style="background-color: {{ $color }}20; border-color: {{ $color }}; color: {{ $color }};">
        <span class="text-[10px] font-bold uppercase tracking-wider block mb-1">
            {{ $viewBy === 'resources' ? 'Dr. ' . $appointment->doctor->name : $appointment->resource->name }}
        </span>
        <p class="text-xs font-bold truncate">
            👤 {{ $appointment->patient->name }}
        </p>
    </div>
@else
    @endif