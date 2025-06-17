<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Transaction Management') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-sm">
                <div class="mx-auto py-4 px-4 sm:px-6 lg:px-8 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between py-5 mb-5">
                        <div class="md:mt-0 sm:flex-none w-72">
                            <form action="{{ route('transactions.index') }}" method="GET">
                                <input type="text" name="search" placeholder="Search transactions..."
                                    class="w-full relative inline-flex items-center px-4 py-2 font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300" />
                            </form>
                        </div>
                        <div class="sm:ml-16 sm:mt-0 sm:flex-none">
                            <a type="button" href="{{ route('transactions.create') }}"
                                class="relative inline-flex items-center px-4 py-2 font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300">
                                Add New Transaction
                            </a>
                        </div>
                    </div>
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-sm text-gray-700 uppercase bg-white dark:bg-gray-800 ">
                                <tr class="bg-white border-t border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="col" class="px-6 py-3 text-center">NO</th>
                                    <th scope="col" class="px-6 py-3 text-center">Customer</th>
                                    <th scope="col" class="px-6 py-3 text-center">Date</th>
                                    <th scope="col" class="px-6 py-3 text-center">Amount</th>
                                    <th scope="col" class="px-6 py-3 text-center">Payment</th>
                                    <th scope="col" class="px-6 py-3 text-center">Status</th>
                                    <th scope="col" class="px-6 py-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4 text-center">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 text-center">{{ $transaction->customer_name }}</td>
                                        <td class="px-6 py-4 text-center">{{ $transaction->date->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 text-center">{{ $transaction->formatted_total_price }}</td>
                                        <td class="px-6 py-4 text-center">{{ ucfirst($transaction->payment_method) }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <span @class([
                                                'px-2 py-1 text-xs font-semibold rounded-full',
                                                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' => $transaction->status == 'completed',
                                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' => $transaction->status == 'pending',
                                                'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' => !in_array($transaction->status, ['completed', 'pending'])
                                            ])>
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </td>
                                        {{-- <td class="px-6 py-4 text-center space-x-2">
                                            <a href="{{ route('transactions.edit', $transaction->transaction_id) }}"
                                               class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                                Edit
                                            </a>
                                            <form class="inline" action="{{ route('transactions.destroy', $transaction->transaction_id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                    Delete
                                                </button>
                                            </form>
                                        </td> --}}
                                        <td class="px-6 py-4 text-center space-x-2">
                                            @if($transaction->status !== 'completed' && $transaction->created_at->addHours(2)->gt(now()))
                                                <a href="{{ route('transactions.edit', $transaction->transaction_id) }}"
                                                class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                                    Edit
                                                </a>
                                            @endif

                                            @if($transaction->status !== 'completed' && $transaction->created_at->addHour()->gt(now()))
                                                <form class="inline" action="{{ route('transactions.destroy', $transaction->transaction_id) }}" method="POST"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            No transactions found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="p-4">
                            {{ $transactions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
