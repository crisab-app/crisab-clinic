<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detalles de la Clínica:') }} <span class="text-indigo-600 dark:text-indigo-400">{{ $clinic->name }}</span>
            </h2>
            
            <a href="{{ route('clinics.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition-colors">
                &larr; Regresar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-6 border-b border-gray-200 dark:border-gray-700 pb-2">Información General</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Nombre de la Clínica</p>
                        <p class="mt-1 text-base text-gray-900 dark:text-gray-100">{{ $clinic->name }}</p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Visual (Identificador único)</p>
                        <p class="mt-1 text-base font-mono text-indigo-600 dark:text-indigo-400">{{ $clinic->visual_id }}</p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Plan de Suscripción</p>
                        <p class="mt-1 text-base text-gray-900 dark:text-gray-100 uppercase font-semibold">
                            {{ $clinic->billing_plan ?? 'TRIAL' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Fecha de Registro</p>
                        <p class="mt-1 text-base text-gray-900 dark:text-gray-100">
                            {{ $clinic->created_at->format('d de M, Y - h:i A') }}
                        </p>
                    </div>

                    @if($clinic->country)
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">País</p>
                        <p class="mt-1 text-base text-gray-900 dark:text-gray-100">{{ $clinic->country }}</p>
                    </div>
                    @endif

                    @if($clinic->timezone)
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Zona Horaria</p>
                        <p class="mt-1 text-base text-gray-900 dark:text-gray-100">{{ $clinic->timezone }}</p>
                    </div>
                    @endif

                </div>
            </div>

            </div>
    </div>
</x-app-layout>