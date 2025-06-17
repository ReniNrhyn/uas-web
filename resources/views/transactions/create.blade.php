<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create New Transaction') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-sm">
                <div class="mx-auto py-4 px-4 sm:px-6 lg:px-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('transactions.store') }}">
                        @csrf

                        <!-- Customer Name -->
                        <div>
                            <x-input-label for="customer_name" :value="__('Customer Name')" />
                            <x-text-input id="customer_name" class="block mt-1 w-full" type="text"
                                name="customer_name" :value="old('customer_name')" required autofocus />
                            <x-input-error :messages="$errors->get('customer_name')" class="mt-2" />
                        </div>

                        <!-- Date -->
                        <div class="mt-4">
                            <x-input-label for="date" :value="__('Transaction Date')" />
                            <x-text-input id="date" class="block mt-1 w-full" type="date"
                                name="date" :value="old('date', now()->format('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('date')" class="mt-2" />
                        </div>

                        <!-- Total Price -->
                        <div class="mt-4">
                            <x-input-label for="total_price" :value="__('Total Amount')" />
                            <div class="relative mt-1 rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <x-text-input id="total_price" class="block w-full pl-10" type="number"
                                    name="total_price" :value="old('total_price')" required
                                    min="0" step="100" placeholder="0" />
                            </div>
                            <x-input-error :messages="$errors->get('total_price')" class="mt-2" />
                        </div>

                        <!-- Payment Method -->
                        <div class="mt-4">
                            <x-input-label for="payment_method" :value="__('Payment Method')" />
                            <select id="payment_method" name="payment_method"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50">
                                <option value="cash">Cash</option>
                                <option value="debit">Debit Card</option>
                                <option value="credit">Credit Card</option>
                                <option value="e-wallet">E-Wallet</option>
                                <option value="transfer">Bank Transfer</option>
                            </select>
                            <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                        </div>

                        <!-- Status -->
                        <div class="mt-4">
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50">
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4 space-x-4">
                            <x-danger-link-button :href="route('transactions.index')">
                                {{ __('Cancel') }}
                            </x-danger-link-button>
                            <x-primary-button>
                                {{ __('Save Transaction') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
