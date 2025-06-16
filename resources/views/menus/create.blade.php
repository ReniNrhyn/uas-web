<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add New Menu') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-sm">
                <div class="mx-auto py-4 px-4 sm:px-6 lg:px-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('menus.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Menu Name -->
                        <div>
                            <x-input-label for="menu_name" :value="__('Menu Name')" />
                            <x-text-input id="menu_name" class="block mt-1 w-full" type="text"
                                name="menu_name" :value="old('menu_name')" required autofocus />
                            <x-input-error :messages="$errors->get('menu_name')" class="mt-2" />
                        </div>

                        <!-- Category Selection -->
                        <div class="mt-4">
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select id="category_id" name="category_id" required
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->category_id }}" {{ old('category_id') == $category->category_id ? 'selected' : '' }}>
                                        {{ $category->category_name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <!-- Price -->
                        <div class="mt-4">
                            <x-input-label for="price" :value="__('Price')" />
                            <x-text-input id="price" class="block mt-1 w-full" type="number" step="0.01"
                                name="price" :value="old('price')" required />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="3"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                            >{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Stock -->
                        <div class="mt-4">
                            <x-input-label for="stock" :value="__('Stock')" />
                            <x-text-input id="stock" class="block mt-1 w-full" type="number"
                                name="stock" :value="old('stock', 0)" required />
                            <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                        </div>

                        <!-- Availability -->
                        <div class="mt-4">
                            <x-input-label for="is_available" :value="__('Availability')" />
                            <div class="mt-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="is_available" value="1"
                                        {{ old('is_available', true) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800">
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Available</span>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('is_available')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-danger-link-button class="ms-4" :href="route('menus.index')">
                                {{ __('Cancel') }}
                            </x-danger-link-button>
                            <x-primary-button class="ms-4">
                                {{ __('Save Menu') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
