<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Transaction Detail') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-sm">
                <div class="mx-auto py-4 px-4 sm:px-6 lg:px-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('detail_transactions.store') }}">
                        @csrf

                        <!-- Transaction Selection -->
                        <div>
                            <x-input-label for="transaction_id" :value="__('Transaction')" />
                            <select id="transaction_id" name="transaction_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50" required>
                                <option value="">-- Select Transaction --</option>
                                @foreach($transactions as $transaction)
                                    <option value="{{ $transaction->id }}" {{ old('transaction_id') == $transaction->id ? 'selected' : '' }}>
                                        #TRX-{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }} - {{ $transaction->student->name ?? 'Guest' }} ({{ $transaction->created_at->format('d M Y') }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('transaction_id')" class="mt-2" />
                        </div>

                        <!-- Menu Selection -->
                        <div class="mt-4">
                            <x-input-label for="menu_id" :value="__('Menu')" />
                            <select id="menu_id" name="menu_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50" required>
                                <option value="">-- Select Menu --</option>
                                @foreach($menus as $menu)
                                    <option value="{{ $menu->id }}" data-price="{{ $menu->price }}" {{ old('menu_id') == $menu->id ? 'selected' : '' }}>
                                        {{ $menu->name }} (Rp {{ number_format($menu->price, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('menu_id')" class="mt-2" />
                        </div>

                        <!-- Quantity -->
                        <div class="mt-4">
                            <x-input-label for="quantity" :value="__('Quantity')" />
                            <x-text-input id="quantity" class="block mt-1 w-full" type="number" name="quantity" :value="old('quantity', 1)" min="1" required />
                            <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                        </div>

                        <!-- Price per Item (auto-filled) -->
                        <div class="mt-4">
                            <x-input-label for="price_per_item" :value="__('Price per Item')" />
                            <x-text-input id="price_per_item" class="block mt-1 w-full" type="text" name="price_per_item" :value="old('price_per_item')" readonly />
                            <x-input-error :messages="$errors->get('price_per_item')" class="mt-2" />
                        </div>

                        <!-- Subtotal (auto-calculated) -->
                        <div class="mt-4">
                            <x-input-label for="subtotal" :value="__('Subtotal')" />
                            <x-text-input id="subtotal" class="block mt-1 w-full font-bold" type="text" name="subtotal" :value="old('subtotal')" readonly />
                            <x-input-error :messages="$errors->get('subtotal')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-danger-link-button class="ms-4" :href="route('detail_transactions.index')">
                                {{ __('Cancel') }}
                            </x-danger-link-button>
                            <x-primary-button class="ms-4">
                                {{ __('Save Transaction Detail') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuSelect = document.getElementById('menu_id');
            const priceInput = document.getElementById('price_per_item');
            const quantityInput = document.getElementById('quantity');
            const subtotalInput = document.getElementById('subtotal');

            // Update price and subtotal when menu changes
            menuSelect.addEventListener('change', function() {
                const selectedOption = menuSelect.options[menuSelect.selectedIndex];
                const price = selectedOption.dataset.price || 0;
                priceInput.value = formatRupiah(price);
                calculateSubtotal();
            });

            // Update subtotal when quantity changes
            quantityInput.addEventListener('input', calculateSubtotal);

            function calculateSubtotal() {
                const price = parseFloat(menuSelect.options[menuSelect.selectedIndex]?.dataset.price || 0);
                const quantity = parseInt(quantityInput.value) || 0;
                const subtotal = price * quantity;
                subtotalInput.value = formatRupiah(subtotal);
            }

            function formatRupiah(amount) {
                return 'Rp ' + parseInt(amount).toLocaleString('id-ID');
            }

            // Initialize values if returning with errors
            if (menuSelect.value) {
                const event = new Event('change');
                menuSelect.dispatchEvent(event);
            }
        });
    </script>
    @endpush
</x-app-layout>
