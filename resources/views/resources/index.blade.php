<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Recursos Físicos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Agregar Nuevo Recurso</h3>
                    
                    <form method="POST" action="{{ route('clinic-resources.store') }}">
                        @csrf

                        <div>
                            <x-input-label for="name" value="Nombre (Ej. Consultorio 1)" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" required autofocus />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="type" value="Tipo de Recurso" />
                            <select id="type" name="type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="consultorio">Consultorio</option>
                                <option value="quirofano">Quirófano</option>
                                <option value="cama">Cama de Hospitalización</option>
                                <option value="equipo">Equipo Médico Especial</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" value="Descripción (Opcional)" />
                            <textarea id="description" name="description" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                Guardar Recurso
                            </x-primary-button>
                        </div>
                    </form>
                </div>

                <div class="md:col-span-2 bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                    <h3 class="text-lg font-medium text-gray-900 p-6 border-b border-gray-100">Recursos Registrados</h3>
                    
                    @if($resources->isEmpty())
                        <div class="p-6 text-gray-500 text-center">
                            No has registrado ningún recurso físico todavía.
                        </div>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($resources as $resource)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $resource->name }}
                                            @if($resource->description)
                                                <div class="text-xs text-gray-500 font-normal">{{ Str::limit($resource->description, 30) }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">
                                            {{ $resource->type }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($resource->is_active)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactivo</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <form action="{{ route('clinic-resources.destroy', $resource) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este recurso?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>