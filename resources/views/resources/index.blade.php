<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestión de Recursos Físicos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-6 font-medium text-sm text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/30 border border-transparent dark:border-green-800 p-4 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 h-fit">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Agregar Nuevo Recurso</h3>
                    
                    <form method="POST" action="{{ route('clinic-resources.store') }}">
                        @csrf

                        <div>
                            <x-input-label for="name" value="Nombre (Ej. Consultorio 1)" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" required autofocus />
                        </div>

                        <div class="mt-4">
                            <div class="flex justify-between items-center">
                                <x-input-label for="type" value="Tipo de Recurso" />
                                <a href="#" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">
                                    + Administrar Tipos
                                </a>
                            </div>
                            <select id="type" name="type" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm transition-colors" required>
                                
                                @forelse($resourceTypes as $type)
                                    <option value="{{ $type->name }}">{{ $type->name }}</option>
                                @empty
                                    <option value="" disabled selected>No hay tipos configurados</option>
                                @endforelse
                                
                            </select>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" value="Descripción (Opcional)" />
                            <textarea id="description" name="description" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm transition-colors"></textarea>
                        </div>

                        <div class="flex items-center justify-end mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                            <x-primary-button class="w-full justify-center">
                                Guardar Recurso
                            </x-primary-button>
                        </div>
                    </form>
                </div>

                <div class="md:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 p-6 border-b border-gray-100 dark:border-gray-700">Recursos Registrados</h3>
                    
                    @if($resources->isEmpty())
                        <div class="p-8 text-gray-500 dark:text-gray-400 text-center flex flex-col items-center">
                            <svg class="w-12 h-12 mb-3 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            No has registrado ningún recurso físico todavía.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nombre</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tipo</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estado</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($resources as $resource)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                                {{ $resource->name }}
                                                @if($resource->description)
                                                    <div class="text-xs text-gray-500 dark:text-gray-400 font-normal mt-1">{{ Str::limit($resource->description, 40) }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300 capitalize">
                                                {{ $resource->type }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                @if($resource->is_active)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">Activo</span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">Inactivo</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <form action="{{ route('clinic-resources.destroy', $resource) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este recurso?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition-colors">Eliminar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>