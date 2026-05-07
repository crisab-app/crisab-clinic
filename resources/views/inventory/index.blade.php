<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center" x-data="{ showCreate: false }">
            <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Catálogo y Almacén') }}
            </h2>
            <button @click="showCreate = true" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-md shadow-sm transition-all text-sm">
                + Nuevo Producto
            </button>

            <template x-if="showCreate">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                        <form action="{{ route('inventory.store') }}" method="POST">
                            @csrf
                            <div class="p-6">
                                <h3 class="text-lg font-bold mb-4">Dar de alta medicamento</h3>
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <x-input-label value="Nombre Comercial" />
                                        <x-text-input name="name" class="w-full mt-1" placeholder="Ej. Aspirina Protect" required />
                                    </div>
                                    <div>
                                        <x-input-label value="Nombre Genérico / Sustancia" />
                                        <x-text-input name="generic_name" class="w-full mt-1" placeholder="Ej. Ácido Acetilsalicílico" />
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <x-input-label value="Presentación" />
                                            <x-text-input name="presentation" class="w-full mt-1" placeholder="Ej. Caja c/30 tabs" />
                                        </div>
                                        <div>
                                            <x-input-label value="Stock Mínimo (Alerta)" />
                                            <x-text-input name="min_stock" type="number" class="w-full mt-1" value="5" required />
                                        </div>
                                    </div>
                                    <div class="flex gap-6 mt-2">
                                        <label class="flex items-center text-sm">
                                            <input type="checkbox" name="is_antibiotic" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="ml-2">Antibiótico</span>
                                        </label>
                                        <label class="flex items-center text-sm">
                                            <input type="checkbox" name="is_controlled" value="1" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                                            <span class="ml-2">Controlado (COFEPRIS)</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-900/50 p-4 flex justify-end gap-3">
                                <button type="button" @click="showCreate = false" class="text-sm font-medium text-gray-600">Cancelar</button>
                                <x-primary-button>Guardar en Catálogo</x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </template>
        </div>
    </x-slot>

    </x-app-layout>