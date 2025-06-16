<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Update Expenditure Category') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-sm">
                <div class="mx-auto py-4 px-4 sm:px-6 lg:px-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('expenditure-categories.update', $category->category_expenditure_id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Category Name -->
                        <div>
                            <x-input-label for="category_name" :value="__('Category Name')" />
                            <x-text-input id="category_name" class="block mt-1 w-full" type="text" name="category_name"
                                :value="$category->category_name ?? old('category_name')" required autofocus autocomplete="category_name" />
                            <x-input-error :messages="$errors->get('category_name')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-danger-link-button class="ms-4" :href="route('expenditure-categories.index')">
                                {{ __('Cancel') }}
                            </x-danger-link-button>
                            <x-primary-button class="ms-4">
                                {{ __('Update Category') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
