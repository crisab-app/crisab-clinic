<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Editar Clínica:') }} <span class="text-indigo-600 dark:text-indigo-400">{{ $clinic->name }}</span>
            </h2>
            
            <a href="{{ route('clinics.show', $clinic->id) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition-colors">
                &larr; Cancelar y Regresar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            
            @if ($errors->any())
                <div class="mb-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded relative">
                    <strong class="font-bold">¡Ups! Hubo un problema.</strong>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 dark:border-gray-700">
    <form action="{{ route('clinics.update', $clinic->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Nombre de la Clínica *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $clinic->name) }}" required 
                                   class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:placeholder-gray-500 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm transition-colors">
                        </div>

                        <div>
                            <label for="phone" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Teléfono de Contacto Administrativo</label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone', $clinic->phone) }}" placeholder="+52 123 456 7890" 
                                   class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:placeholder-gray-500 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm transition-colors">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Útil para recordatorios de cobro o promociones (Soporta formato internacional).</p>
                        </div>

                        <div>
                            <label for="billing_plan" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Plan de Suscripción Manual</label>
                            <select name="billing_plan" id="billing_plan" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm transition-colors">
                                <option value="TRIAL" {{ old('billing_plan', $clinic->billing_plan) == 'TRIAL' ? 'selected' : '' }}>Prueba Gratuita (TRIAL)</option>
                                <option value="BASIC" {{ old('billing_plan', $clinic->billing_plan) == 'BASIC' ? 'selected' : '' }}>Plan Básico</option>
                                <option value="PRO" {{ old('billing_plan', $clinic->billing_plan) == 'PRO' ? 'selected' : '' }}>Plan Profesional</option>
                                <option value="PREMIUM" {{ old('billing_plan', $clinic->billing_plan) == 'PREMIUM' ? 'selected' : '' }}>Plan Premium</option>
                            </select>
                            <p class="mt-1 text-xs text-orange-600 dark:text-orange-400">Nota: Al integrar Stripe, este campo se actualizará automáticamente con los pagos.</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end border-t border-gray-200 dark:border-gray-700 pt-4 mt-6">
                        <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">
                            Guardar Cambios
                        </x-primary-button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>