<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Editar Clínica: <span class="text-indigo-500">{{ $clinic->name }}</span>
            </h2>
            <a href="{{ route('clinics.index') }}" class="px-4 py-2 border border-gray-600 rounded-md text-sm text-gray-300 hover:bg-gray-800 uppercase tracking-widest transition-colors">
                &larr; Cancelar y Regresar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('status'))
                <div class="mb-4 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 p-4 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg border border-gray-700 p-6 md:p-8">
                
                <!-- IMPORTANTE: enctype="multipart/form-data" es vital para poder subir el logotipo -->
                <form action="{{ route('clinics.update', $clinic->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Nombre -->
                    <div>
                        <x-input-label for="name" value="Nombre de la Clínica *" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $clinic->name)" required />
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <x-input-label for="phone" value="Teléfono de Contacto Administrativo" />
                        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $clinic->phone)" />
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Útil para recordatorios de cobro o promociones (Soporta formato internacional).</p>
                    </div>

                    <!-- Dirección (NUEVO) -->
                    <div>
                        <x-input-label for="address" value="Dirección Completa (Membrete)" />
                        <textarea id="address" name="address" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('address', $clinic->address) }}</textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Esta dirección aparecerá automáticamente en el pie de página de las recetas médicas impresas.</p>
                    </div>

                    <!-- Logotipo (NUEVO) -->
                    <div class="p-5 border border-gray-200 dark:border-gray-700 rounded-md bg-gray-50 dark:bg-gray-900/50">
                        <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">Logotipo Oficial</h3>
                        <div class="flex flex-col sm:flex-row items-center gap-6">
                            
                            <!-- Previsualizador del Logo Actual -->
                            <div class="w-32 h-32 flex-shrink-0 flex items-center justify-center bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md overflow-hidden shadow-inner">
                                @if($clinic->logo_path)
                                    <img src="{{ asset('storage/' . $clinic->logo_path) }}" alt="Logo" class="max-w-full max-h-full object-contain p-2">
                                @else
                                    <span class="text-xs text-gray-500 dark:text-gray-400 text-center px-2">Sin Logotipo</span>
                                @endif
                            </div>
                            
                            <!-- Input para subir archivo -->
                            <div class="flex-1 w-full">
                                <input type="file" id="logo" name="logo" accept="image/png, image/jpeg" class="block w-full text-sm text-gray-500 dark:text-gray-400
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-md file:border-0
                                  file:text-sm file:font-bold
                                  file:bg-indigo-50 file:text-indigo-700
                                  dark:file:bg-indigo-900/50 dark:file:text-indigo-400
                                  hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900
                                  cursor-pointer border border-gray-300 dark:border-gray-700 rounded-md p-1 bg-white dark:bg-gray-900"
                                />
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                    Formatos: PNG o JPG transparente. Tamaño máximo: 2MB.<br>
                                    Este logotipo encabezará los PDFs de las consultas.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Plan de Suscripción (Protegido para que solo Superadmin lo vea/edite) -->
                    @role('Superadmin')
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <x-input-label for="billing_plan" value="Plan de Suscripción Manual (Solo Superadmin)" class="text-orange-500" />
                        <select id="billing_plan" name="billing_plan" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md">
                            <option value="TRIAL" {{ $clinic->billing_plan == 'TRIAL' ? 'selected' : '' }}>Prueba Gratuita (TRIAL)</option>
                            <option value="BASIC" {{ $clinic->billing_plan == 'BASIC' ? 'selected' : '' }}>Básico (BASIC)</option>
                            <option value="PRO" {{ $clinic->billing_plan == 'PRO' ? 'selected' : '' }}>Profesional (PRO)</option>
                            <option value="PREMIUM" {{ $clinic->billing_plan == 'PREMIUM' ? 'selected' : '' }}>Premium (PREMIUM)</option>
                        </select>
                        <p class="text-xs text-orange-500 mt-1">Nota: Al integrar Stripe, este campo se actualizará automáticamente con los pagos.</p>
                    </div>
                    @else
                        <!-- Si es un admin de clínica, pasamos su plan de forma oculta para no romper la validación al guardar -->
                        <input type="hidden" name="billing_plan" value="{{ $clinic->billing_plan }}">
                    @endrole

                    <!-- Botón Guardar -->
                    <div class="flex justify-end pt-6">
                        <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-md transition-transform transform hover:-translate-y-0.5">
                            GUARDAR CAMBIOS
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>