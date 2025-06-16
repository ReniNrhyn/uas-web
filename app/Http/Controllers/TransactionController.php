<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::with(['user', 'details.menu'])
            ->when(request()->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . request()->search . '%');
                })
                ->orWhere('transaction_id', 'like', '%' . request()->search . '%');
            })
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('transactions.index', compact('transactions'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $menus = Menu::all();
        return view('transactions.create', compact('users', 'menus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'payment_method' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,menu_id',
            'items.*.quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();
        try {
            // Create transaction
            $transaction = Transaction::create([
                'user_id' => $request->user_id,
                'date' => $request->date,
                'payment_method' => $request->payment_method,
                'total_price' => $request->total_price
            ]);

            // Create transaction details
            foreach ($request->items as $item) {
                $menu = Menu::find($item['menu_id']);
                TransactionDetail::create([
                    'transaction_id' => $transaction->transaction_id,
                    'menu_id' => $item['menu_id'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $menu->price * $item['quantity']
                ]);
            }

            DB::commit();
            return redirect()->route('transactions.index')
                ->with('success', 'Transaction #'.$transaction->transaction_id.' has been created successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $transaction = Transaction::with(['user', 'details.menu'])->findOrFail($id);
        return view('transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $transaction = Transaction::with(['details'])->findOrFail($id);
        $users = User::all();
        $menus = Menu::all();
        return view('transactions.edit', compact('transaction', 'users', 'menus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'payment_method' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,menu_id',
            'items.*.quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();
        try {
            $transaction = Transaction::findOrFail($id);

            // Update transaction
            $transaction->update([
                'user_id' => $request->user_id,
                'date' => $request->date,
                'payment_method' => $request->payment_method,
                'total_price' => $request->total_price
            ]);

            // Delete existing details
            $transaction->details()->delete();

            // Create new transaction details
            foreach ($request->items as $item) {
                $menu = Menu::find($item['menu_id']);
                TransactionDetail::create([
                    'transaction_id' => $transaction->transaction_id,
                    'menu_id' => $item['menu_id'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $menu->price * $item['quantity']
                ]);
            }

            DB::commit();
            return redirect()->route('transactions.index')
                ->with('success', 'Transaction #'.$transaction->transaction_id.' has been updated successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $transaction = Transaction::findOrFail($id);
            $transactionId = $transaction->transaction_id;

            // Delete transaction details first
            $transaction->details()->delete();

            // Then delete the transaction
            $transaction->delete();

            DB::commit();
            return redirect()->route('transactions.index')
                ->with('success', 'Transaction #'.$transactionId.' has been deleted successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }
}
