<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Tablero de Recepción
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg mb-6 p-4 inline-block">
                <form method="GET" action="{{ route('appointments.index') }}" id="agendaFilters" class="flex flex-col sm:flex-row gap-6 items-end">
                    
                    <div>
                        <x-input-label for="date" value="Día a Consultar" />
                        <x-text-input type="text" name="date" id="date" value="{{ $selectedDate }}" class="mt-1 block w-48 mi-calendario bg-gray-50 cursor-pointer" placeholder="Selecciona un día..." readonly />
                    </div>

                    <div>
                        <x-input-label value="Ver Agenda Por" />
                        <select name="view_by" onchange="this.form.submit()" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="doctors" {{ $viewBy === 'doctors' ? 'selected' : '' }}>Médicos</option>
                            <option value="resources" {{ $viewBy === 'resources' ? 'selected' : '' }}>Consultorios</option>
                        </select>
                    </div>

                    <div>
                        <x-input-label value="Diseño del Tablero" />
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <label class="cursor-pointer">
                                <input type="radio" name="layout" value="vertical" class="sr-only peer" onchange="this.form.submit()" {{ $layout === 'vertical' ? 'checked' : '' }}>
                                <span class="px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-700 dark:text-gray-300 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900 peer-checked:text-indigo-600 dark:peer-checked:text-indigo-300 peer-checked:border-indigo-500 rounded-l-md hover:bg-gray-50 transition-colors">
                                    Horas en Filas (↓)
                                </span>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="layout" value="horizontal" class="sr-only peer" onchange="this.form.submit()" {{ $layout === 'horizontal' ? 'checked' : '' }}>
                                <span class="px-4 py-2 border border-l-0 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-700 dark:text-gray-300 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900 peer-checked:text-indigo-600 dark:peer-checked:text-indigo-300 peer-checked:border-indigo-500 rounded-r-md hover:bg-gray-50 transition-colors">
                                    Doctores en Filas (→)
                                </span>
                            </label>
                        </div>
                    </div>

                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        
                        @if($layout === 'vertical')
                            <thead>
                                <tr>
                                    <th class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 text-left text-xs font-bold text-gray-500 uppercase tracking-wider sticky left-0 z-20 w-24 border-r dark:border-gray-700">HORA</th>
                                    @foreach($headers as $header)
                                        <th class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 text-center text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider border-r border-gray-200 dark:border-gray-700 min-w-[200px]">
                                            {{ $viewBy === 'resources' ? $header->name : 'Dr. ' . $header->name }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($timeSlots as $time => $slots)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-gray-100 sticky left-0 bg-white dark:bg-gray-800 z-10 border-r border-gray-200 dark:border-gray-700">{{ $time }}</td>
                                        @foreach($headers as $header)
                                            @php $appointment = $slots[$header->id]; @endphp
                                            <td class="p-2 border-r border-gray-200 dark:border-gray-700 relative align-top h-16">
                                                @include('appointments.partials.grid-cell', ['header' => $header])
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>

                        @else
                            <thead>
                                <tr>
                                    <th class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 text-left text-xs font-bold text-gray-500 uppercase tracking-wider sticky left-0 z-20 border-r border-gray-200 dark:border-gray-700 min-w-[200px]">
                                        {{ $viewBy === 'resources' ? 'CONSULTORIO' : 'DOCTOR' }}
                                    </th>
                                    @foreach($timeSlots as $time => $slots)
                                        <th class="px-4 py-2 bg-gray-50 dark:bg-gray-900/50 text-center text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider border-r border-gray-200 dark:border-gray-700 min-w-[150px]">
                                            {{ $time }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($headers as $header)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-gray-100 sticky left-0 bg-white dark:bg-gray-800 z-10 border-r border-gray-200 dark:border-gray-700">
                                            {{ $viewBy === 'resources' ? $header->name : 'Dr. ' . $header->name }}
                                        </td>
                                        @foreach($timeSlots as $time => $slots)
                                            @php $appointment = $slots[$header->id]; @endphp
                                            <td class="p-2 border-r border-gray-200 dark:border-gray-700 relative align-top h-20">
                                                @include('appointments.partials.grid-cell', ['header' => $header])
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        @endif

                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr(".mi-calendario", {
                locale: "es",
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "j F, Y",
                disableMobile: true,
                onChange: function(selectedDates, dateStr, instance) {
                    document.getElementById('agendaFilters').submit();
                }
            });
        });
    </script>
</x-app-layout>