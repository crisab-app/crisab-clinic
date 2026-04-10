<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Editar Empleado:') }} <span class="text-indigo-600 dark:text-indigo-400">{{ $staffMember->name }}</span>
            </h2>
            <a href="{{ route('staff.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                &larr; Cancelar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 dark:border-gray-700">
                <form action="{{ route('staff.update', $staffMember->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <x-input-label for="name" :value="__('Nombre Completo')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $staffMember->name)" required autofocus />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Correo Electrónico')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $staffMember->email)" required />
                        </div>
                    </div>

                    <div class="mb-8 bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                        <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200 mb-4 uppercase tracking-wider border-b border-gray-200 dark:border-gray-600 pb-2">Matriz de Accesos</h4>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($permissions as $permission)
                                <label class="flex items-center p-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-md cursor-pointer hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                           {{ $staffMember->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:border-gray-500 dark:bg-gray-900 dark:checked:bg-indigo-600 w-5 h-5">
                                    <span class="ms-3 text-sm font-medium text-gray-700 dark:text-gray-300 capitalize">
                                        {{ str_replace('modulo_', '', $permission->name) }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-6">
                        <x-primary-button>
                            Guardar Cambios
                        </x-primary-button>
                </form>

                        <form action="{{ route('staff.destroy', $staffMember->id) }}" method="POST" onsubmit="return confirm('¿Estás SEGURO de dar de baja a este empleado? Perderá acceso a la clínica inmediatamente.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 font-semibold underline">
                                Dar de baja al empleado
                            </button>
                        </form>
                    </div>
            </div>

        </div>
    </div>
</x-app-layout>