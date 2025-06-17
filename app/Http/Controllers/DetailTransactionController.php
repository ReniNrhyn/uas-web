<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaction;
use App\Models\Transaction;
use App\Models\Menu;
use Illuminate\Http\Request;

class DetailTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $detailTransactions = DetailTransaction::with(['transaction', 'menu'])
            ->when(request()->search, function ($query) {
                $query->where('transaction_id', 'like', '%' . request()->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('detail_transactions.index', compact('detailTransactions'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $transactions = Transaction::latest()->get();
        $menus = Menu::where('is_available', true)->get();

        return view('detail_transactions.create', compact('transactions', 'menus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
            'price_per_item' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0'
        ]);

        try {
            $detailTransaction = DetailTransaction::create([
                'transaction_id' => $request->transaction_id,
                'menu_id' => $request->menu_id,
                'quantity' => $request->quantity,
                'price_per_item' => $request->price_per_item,
                'subtotal' => $request->subtotal
            ]);

            // Update total in parent transaction
            $this->updateTransactionTotal($request->transaction_id);

            return redirect()->route('detail_transactions.index')
                ->with('success', 'Transaction detail has been added successfully!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $detailTransaction = DetailTransaction::findOrFail($id);
        $menus = Menu::where('is_available', true)->get();

        return view('detail_transactions.edit', compact('detailTransaction', 'menus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
            'price_per_item' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0'
        ]);

        try {
            $detailTransaction = DetailTransaction::findOrFail($id);

            $detailTransaction->update([
                'menu_id' => $request->menu_id,
                'quantity' => $request->quantity,
                'price_per_item' => $request->price_per_item,
                'subtotal' => $request->subtotal
            ]);

            // Update total in parent transaction
            $this->updateTransactionTotal($detailTransaction->transaction_id);

            return redirect()->route('detail_transactions.index')
                ->with('success', 'Transaction detail has been updated successfully!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $detailTransaction = DetailTransaction::findOrFail($id);
            $transactionId = $detailTransaction->transaction_id;

            $detailTransaction->delete();

            // Update total in parent transaction
            $this->updateTransactionTotal($transactionId);

            return redirect()->route('detail_transactions.index')
                ->with('success', 'Transaction detail has been deleted successfully!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Update the total amount in the parent transaction
     */
    private function updateTransactionTotal($transactionId)
    {
        $transaction = Transaction::find($transactionId);
        if ($transaction) {
            $transaction->total = $transaction->details()->sum('subtotal');
            $transaction->save();
        }
    }

    // Method untuk menampilkan detail transaksi berdasarkan transaction_id tertentu
    public function indexByTransaction(Transaction $transaction)
    {
        $detailTransactions = $transaction->details()
            ->with('menu')
            ->paginate(10);

        return view('detail_transactions.index', [
            'detailTransactions' => $detailTransactions,
            'transaction' => $transaction
        ]);
    }

    // Method untuk menampilkan form create dengan transaction_id yang sudah ditentukan
    public function createForTransaction(Transaction $transaction)
    {
        $menus = Menu::where('is_available', true)->get();
        return view('detail_transactions.create', [
            'transaction' => $transaction,
            'menus' => $menus
        ]);
    }

    // Method untuk menyimpan detail transaksi dengan transaction_id yang sudah ditentukan
    public function storeForTransaction(Request $request, Transaction $transaction)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
            'price_per_item' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0'
        ]);

        try {
            $detailTransaction = $transaction->details()->create([
                'menu_id' => $request->menu_id,
                'quantity' => $request->quantity,
                'price_per_item' => $request->price_per_item,
                'subtotal' => $request->subtotal
            ]);

            $this->updateTransactionTotal($transaction->id);

            return redirect()->route('transactions.details.index', $transaction)
                ->with('success', 'Transaction detail has been added successfully!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

}
