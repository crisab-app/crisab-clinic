<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Centro de Catálogos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-6 font-medium text-sm text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/30 border border-transparent dark:border-green-800 p-4 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">Tipos de Recurso Físico</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Ej: Consultorio, Quirófano, Cabina de Masaje, Unidad Dental.</p>
                        
                        <form action="{{ route('resource-types.store') }}" method="POST" class="flex gap-2">
                            @csrf
                            <x-text-input name="name" type="text" class="block w-full" placeholder="Nuevo tipo de recurso..." required />
                            <x-primary-button>Agregar</x-primary-button>
                        </form>
                    </div>
                    
                    <div class="p-0">
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($resourceTypes as $type)
                                <li class="flex justify-between items-center p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <span class="text-gray-800 dark:text-gray-200">{{ $type->name }}</span>
                                    <form action="{{ route('resource-types.destroy', $type->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este tipo de recurso?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-semibold">Eliminar</button>
                                    </form>
                                </li>
                            @empty
                                <li class="p-6 text-center text-gray-500 dark:text-gray-400 text-sm">No hay tipos registrados.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">Especialidades</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Ej: Pediatría, Odontología, Cosmetología, Fisioterapia.</p>
                        
                        <form action="{{ route('specialties.store') }}" method="POST" class="flex gap-2">
                            @csrf
                            <x-text-input name="name" type="text" class="block w-full" placeholder="Nueva especialidad..." required />
                            <x-primary-button>Agregar</x-primary-button>
                        </form>
                    </div>
                    
                    <div class="p-0">
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($specialties as $specialty)
                                <li class="flex justify-between items-center p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <span class="text-gray-800 dark:text-gray-200">{{ $specialty->name }}</span>
                                    <form action="{{ route('specialties.destroy', $specialty->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta especialidad?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-semibold">Eliminar</button>
                                    </form>
                                </li>
                            @empty
                                <li class="p-6 text-center text-gray-500 dark:text-gray-400 text-sm">No hay especialidades registradas.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>