<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Administración de Clínicas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/30 border border-transparent dark:border-green-800 p-4 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6 border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Registrar Nueva Clínica</h3>
                
                <form action="{{ route('clinics.store') }}" method="POST" class="flex gap-4">
                    @csrf
                    <input type="text" name="name" placeholder="Nombre de la Clínica" required 
                           class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:placeholder-gray-500 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm flex-1 transition-colors">
                    
                    <x-primary-button>Guardar Clínica</x-primary-button>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Clínicas Registradas</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Nombre</th>
                                <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">ID Visual</th>
                                <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Plan</th>
                                <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Fecha de Registro</th>
                                <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clinics as $clinic)
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="py-3 text-gray-900 dark:text-gray-200">{{ $clinic->name }}</td>
                                    <td class="py-3 font-mono text-indigo-600 dark:text-indigo-400">{{ $clinic->visual_id }}</td>
                                    <td class="py-3 uppercase text-xs font-bold text-gray-500 dark:text-gray-400">{{ $clinic->billing_plan ?? 'TRIAL' }}</td>
                                    <td class="py-3 text-gray-600 dark:text-gray-300">{{ $clinic->created_at->format('d/m/Y') }}</td>
                                    
                                    <td class="py-3 text-right">
                                        <a href="{{ route('clinics.show', $clinic->id) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 hover:underline">
                                            Ver Detalles
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>