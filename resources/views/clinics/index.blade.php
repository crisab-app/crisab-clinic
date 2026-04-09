<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestión de Personal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('status'))
                <div class="mb-6 font-medium text-sm text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/30 border border-transparent dark:border-green-800 p-4 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 dark:border-gray-700 h-fit">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Agregar Miembro</h3>
                    
                    <form action="{{ route('staff.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Nombre Completo')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required autofocus />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="email" :value="__('Correo Electrónico')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" required />
                        </div>

                        <div class="mb-6 bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 uppercase tracking-wider">Módulos de Acceso</h4>
                            
                            <div class="space-y-3">
                                @forelse($permissions as $permission)
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-900 dark:checked:bg-indigo-600">
                                        <span class="ms-3 text-sm text-gray-700 dark:text-gray-300 capitalize">
                                            {{ str_replace('modulo_', '', $permission->name) }}
                                        </span>
                                    </label>
                                @empty
                                    <p class="text-xs text-gray-500 dark:text-gray-400">No hay permisos definidos en el sistema aún.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <x-primary-button class="w-full justify-center">Enviar Invitación</x-primary-button>
                        </div>
                    </form>
                </div>

                <div class="md:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Equipo de la Clínica</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Nombre</th>
                                    <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Accesos</th>
                                    <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($staff as $member)
                                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <td class="py-3">
                                            <div class="text-gray-900 dark:text-gray-200 font-medium">{{ $member->name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $member->email }}</div>
                                        </td>
                                        
                                        <td class="py-3">
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($member->permissions as $perm)
                                                    <span class="px-2 py-1 text-[10px] uppercase tracking-wider font-semibold rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                                        {{ str_replace('modulo_', '', $perm->name) }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        
                                        <td class="py-3 text-right">
                                            <button class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Editar</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-4 text-center text-gray-500 dark:text-gray-400">
                                            Aún no has agregado a nadie a tu equipo.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>