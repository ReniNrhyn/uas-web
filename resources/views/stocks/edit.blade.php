<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Update Stock') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-sm">
                <div class="mx-auto py-4 px-4 sm:px-6 lg:px-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('stocks.update', $stock->stock_id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Stock Name -->
                        <div>
                            <x-input-label for="stock_name" :value="__('Stock Name')" />
                            <x-text-input id="stock_name" class="block mt-1 w-full" type="text" name="stock_name"
                                :value="$stock->stock_name ?? old('stock_name')" required autofocus />
                            <x-input-error :messages="$errors->get('stock_name')" class="mt-2" />
                        </div>

                        <!-- Unit -->
                        <div class="mt-4">
                            <x-input-label for="unit" :value="__('Unit')" />
                            <x-text-input id="unit" class="block mt-1 w-full" type="text" name="unit"
                                :value="$stock->unit ?? old('unit')" required />
                            <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                        </div>

                        <!-- Minimum Stock -->
                        <div class="mt-4">
                            <x-input-label for="minimum_stock" :value="__('Minimum Stock')" />
                            <x-text-input id="minimum_stock" class="block mt-1 w-full" type="number"
                                name="minimum_stock" :value="$stock->minimum_stock ?? old('minimum_stock')"
                                required min="0" />
                            <x-input-error :messages="$errors->get('minimum_stock')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-danger-link-button class="ms-4" :href="route('stocks.index')">
                                {{ __('Back') }}
                            </x-danger-link-button>
                            <x-primary-button class="ms-4">
                                {{ __('Update') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
