<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Configuración de la Clínica') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-6 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 p-4 rounded-xl border border-green-200 dark:border-green-800 flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-xl overflow-hidden sm:rounded-2xl border border-gray-100 dark:border-gray-700">
                
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                    <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white">
                        Datos Generales y Membrete
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                        Esta información es la que aparecerá impresa en las recetas médicas y documentos oficiales de tus pacientes.
                    </p>
                </div>

                <!-- IMPORTANTE: enctype="multipart/form-data" es obligatorio para subir imágenes -->
                <form action="{{ route('clinic.update') }}" method="POST" enctype="multipart/form-data" class="px-6 py-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Columna Izquierda: Datos de texto -->
                        <div class="space-y-6">
                            <div>
                                <x-input-label for="name" value="Nombre Oficial de la Clínica" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $clinic->name)" required />
                                <p class="text-xs text-gray-500 mt-1">Ej. Centro Médico Integral S.A. de C.V.</p>
                            </div>

                            <div>
                                <x-input-label for="phone" value="Teléfono Principal" />
                                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $clinic->phone)" />
                            </div>

                            <div>
                                <x-input-label for="address" value="Dirección Completa" />
                                <textarea id="address" name="address" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('address', $clinic->address) }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Incluye calle, número, colonia, código postal y ciudad.</p>
                            </div>
                        </div>

                        <!-- Columna Derecha: Logotipo -->
                        <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-xl border border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center text-center">
                            
                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">Logotipo Actual</h4>
                            
                            <div class="w-full flex justify-center mb-6">
                                @if($clinic->logo_path)
                                    <img src="{{ asset('storage/' . $clinic->logo_path) }}" alt="Logo" class="max-h-32 object-contain bg-white p-2 rounded-lg shadow-sm border border-gray-200">
                                @else
                                    <div class="h-24 w-24 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400">
                                        Sin Logo
                                    </div>
                                @endif
                            </div>

                            <div class="w-full">
                                <x-input-label for="logo" value="Subir Nuevo Logotipo" class="text-left mb-1" />
                                <input type="file" id="logo" name="logo" accept="image/png, image/jpeg" class="block w-full text-sm text-gray-500 dark:text-gray-400
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-md file:border-0
                                  file:text-sm file:font-bold
                                  file:bg-indigo-50 file:text-indigo-700
                                  dark:file:bg-indigo-900/50 dark:file:text-indigo-400
                                  hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900
                                  transition-colors cursor-pointer border border-gray-300 dark:border-gray-700 rounded-md p-1 bg-white dark:bg-gray-900
                                "/>
                                <p class="text-xs text-gray-500 mt-2">Formatos aceptados: PNG o JPG. Tamaño máximo: 2MB.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Botón Guardar -->
                    <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md transform transition hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Guardar Configuración
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>