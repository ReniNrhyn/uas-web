<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Update Transaction') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-sm">
                <div class="mx-auto py-4 px-4 sm:px-6 lg:px-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('transactions.update', $transaction->transaction_id) }}">
                        @csrf
                        @method('PUT')

                        <!-- User Selection -->
                        <div>
                            <x-input-label for="user_id" :value="__('Customer')" />
                            <select id="user_id" name="user_id" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:ring focus:ring-opacity-50" required>
                                <option value="">-- Select Customer --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ $transaction->user_id == $user->id || old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                        </div>

                        <!-- Date -->
                        <div class="mt-4">
                            <x-input-label for="date" :value="__('Transaction Date')" />
                            <x-text-input id="date" class="block mt-1 w-full" type="date" name="date"
                                :value="old('date', $transaction->date->format('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('date')" class="mt-2" />
                        </div>

                        <!-- Payment Method -->
                        <div class="mt-4">
                            <x-input-label for="payment_method" :value="__('Payment Method')" />
                            <select id="payment_method" name="payment_method" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:ring focus:ring-opacity-50" required>
                                <option value="">-- Select Payment Method --</option>
                                <option value="cash" {{ $transaction->payment_method == 'cash' || old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="debit" {{ $transaction->payment_method == 'debit' || old('payment_method') == 'debit' ? 'selected' : '' }}>Debit Card</option>
                                <option value="credit" {{ $transaction->payment_method == 'credit' || old('payment_method') == 'credit' ? 'selected' : '' }}>Credit Card</option>
                                <option value="transfer" {{ $transaction->payment_method == 'transfer' || old('payment_method') == 'transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="e-wallet" {{ $transaction->payment_method == 'e-wallet' || old('payment_method') == 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
                            </select>
                            <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                        </div>

                        <!-- Transaction Items -->
                        <div class="mt-4">
                            <x-input-label :value="__('Transaction Items')" />
                            <div id="transaction-items" class="space-y-4">
                                @foreach($transaction->details as $index => $detail)
                                <div class="item-row flex gap-4 items-end">
                                    <div class="flex-1">
                                        <x-input-label for="menu_id_{{ $index }}" :value="__('Menu Item')" />
                                        <select name="items[{{ $index }}][menu_id]" id="menu_id_{{ $index }}" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:ring focus:ring-opacity-50 menu-select" required>
                                            <option value="">-- Select Menu --</option>
                                            @foreach($menus as $menu)
                                                <option value="{{ $menu->menu_id }}"
                                                    data-price="{{ $menu->price }}"
                                                    {{ $detail->menu_id == $menu->menu_id || old("items.$index.menu_id") == $menu->menu_id ? 'selected' : '' }}>
                                                    {{ $menu->name }} (Rp {{ number_format($menu->price, 0, ',', '.') }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="w-24">
                                        <x-input-label for="quantity_{{ $index }}" :value="__('Qty')" />
                                        <x-text-input type="number" name="items[{{ $index }}][quantity]" id="quantity_{{ $index }}"
                                            min="1" value="{{ old("items.$index.quantity", $detail->quantity) }}"
                                            class="quantity-input block mt-1 w-full" required />
                                    </div>
                                    <div class="w-32">
                                        <x-input-label :value="__('Subtotal')" />
                                        <x-text-input type="text" value="{{ number_format($detail->subtotal, 0, ',', '.') }}"
                                            class="subtotal-input block mt-1 w-full" readonly />
                                    </div>
                                    @if($index > 0)
                                    <button type="button" class="remove-item px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                                        ×
                                    </button>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                            <button type="button" id="add-item" class="mt-2 px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                                + Add Item
                            </button>
                        </div>

                        <!-- Total Price (auto-calculated) -->
                        <div class="mt-4">
                            <x-input-label for="total_price" :value="__('Total Price')" />
                            <x-text-input id="total_price" class="block mt-1 w-full" type="text" name="total_price"
                                :value="old('total_price', $transaction->total_price)" readonly />
                            <x-input-error :messages="$errors->get('total_price')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-danger-link-button class="ms-4" :href="route('transactions.index')">
                                {{ __('Cancel') }}
                            </x-danger-link-button>
                            <x-primary-button class="ms-4">
                                {{ __('Update Transaction') }}
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
            const itemsContainer = document.getElementById('transaction-items');
            const addItemButton = document.getElementById('add-item');
            const totalPriceInput = document.getElementById('total_price');

            // Template for new items
            const itemTemplate = `
                <div class="item-row flex gap-4 items-end">
                    <div class="flex-1">
                        <x-input-label :value="__('Menu Item')" />
                        <select name="items[__INDEX__][menu_id]" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:ring focus:ring-opacity-50 menu-select" required>
                            <option value="">-- Select Menu --</option>
                            @foreach($menus as $menu)
                                <option value="{{ $menu->menu_id }}" data-price="{{ $menu->price }}">
                                    {{ $menu->name }} (Rp {{ number_format($menu->price, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-24">
                        <x-input-label :value="__('Qty')" />
                        <x-text-input type="number" name="items[__INDEX__][quantity]" min="1" value="1"
                            class="quantity-input block mt-1 w-full" required />
                    </div>
                    <div class="w-32">
                        <x-input-label :value="__('Subtotal')" />
                        <x-text-input type="text" class="subtotal-input block mt-1 w-full" readonly />
                    </div>
                    <button type="button" class="remove-item px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                        ×
                    </button>
                </div>
            `;

            // Add item button click handler
            addItemButton.addEventListener('click', function() {
                const newIndex = document.querySelectorAll('.item-row').length;
                const newItem = itemTemplate.replace(/__INDEX__/g, newIndex);
                itemsContainer.insertAdjacentHTML('beforeend', newItem);

                // Add event listeners to the new item
                const newRow = itemsContainer.lastElementChild;
                setupItemRow(newRow);
                calculateTotal();
            });

            // Setup existing items
            document.querySelectorAll('.item-row').forEach(row => {
                setupItemRow(row);
            });

            // Calculate initial total
            calculateTotal();

            function setupItemRow(row) {
                const menuSelect = row.querySelector('.menu-select');
                const quantityInput = row.querySelector('.quantity-input');
                const subtotalInput = row.querySelector('.subtotal-input');
                const removeBtn = row.querySelector('.remove-item');

                // Update subtotal when menu or quantity changes
                menuSelect?.addEventListener('change', function() {
                    updateSubtotal(menuSelect, quantityInput, subtotalInput);
                    calculateTotal();
                });

                quantityInput?.addEventListener('input', function() {
                    updateSubtotal(menuSelect, quantityInput, subtotalInput);
                    calculateTotal();
                });

                // Remove item button
                removeBtn?.addEventListener('click', function() {
                    row.remove();
                    calculateTotal();
                });

                // Initial subtotal calculation
                if (menuSelect && quantityInput && subtotalInput) {
                    updateSubtotal(menuSelect, quantityInput, subtotalInput);
                }
            }

            function calculateTotal() {
                let total = 0;
                const itemRows = itemsContainer.querySelectorAll('.item-row');

                itemRows.forEach(row => {
                    const subtotalInput = row.querySelector('.subtotal-input');
                    total += parseFloat(subtotalInput?.value.replace(/[^\d]/g, '') || 0);
                });

                totalPriceInput.value = total.toLocaleString('id-ID');
            }

            function updateSubtotal(menuSelect, quantityInput, subtotalInput) {
                const price = menuSelect?.selectedOptions[0]?.dataset.price || 0;
                const quantity = quantityInput?.value || 0;
                const subtotal = price * quantity;
                subtotalInput.value = subtotal.toLocaleString('id-ID');
            }
        });
    </script>
    @endpush
</x-app-layout>
