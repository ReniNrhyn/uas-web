<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::when(request()->search, function ($query) {
            $query->where('customer_name', 'like', '%' . request()->search . '%');
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
        return view('transactions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'date' => 'required|date',
            'total_price' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'status' => 'required|string'
        ]);

        DB::beginTransaction();
        try {
            $transaction = new Transaction([
                'customer_name' => $request->customer_name,
                'date' => $request->date,
                'total_price' => $request->total_price,
                'payment_method' => $request->payment_method,
                'status' => $request->status
            ]);

            $transaction->save();

            DB::commit();
            return redirect()->route('transactions.index')
                ->with('success', 'Transaction for '.$transaction->customer_name.' has been added successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        return view('transactions.edit', compact('transaction'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        // Batas waktu edit (2 jam setelah transaksi)
        $editDeadline = $transaction->created_at->addHours(2);

        if (now()->gt($editDeadline)) {
            return back()->with('error', 'Transaksi hanya bisa diedit dalam 2 jam setelah dibuat.');
        }

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'date' => 'required|date',
            'total_price' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'status' => 'required|string'
        ]);

        DB::beginTransaction();
        // try {
        //     $transaction->customer_name = $request->customer_name;
        //     $transaction->date = $request->date;
        //     $transaction->total_price = $request->total_price;
        //     $transaction->payment_method = $request->payment_method;
        //     $transaction->status = $request->status;

        //     $transaction->save();

        //     DB::commit();
        //     return redirect()->route('transactions.index')
        //         ->with('success', 'Transaction for '.$transaction->customer_name.' has been updated successfully!');
        // } catch (\Throwable $th) {
        //     DB::rollBack();
        //     return back()->with('error', $th->getMessage());
        // }
                try {
            // Hanya boleh edit jika status belum completed
            if ($transaction->status === 'completed') {
                return back()->with('error', 'Transaksi yang sudah completed tidak bisa diedit.');
            }

            $transaction->update($request->all());

            DB::commit();
            return redirect()->route('transactions.index')
                ->with('success', 'Transaksi berhasil diperbarui!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
                // Batas waktu delete (1 jam setelah transaksi)
        $deleteDeadline = $transaction->created_at->addHour();

        if (now()->gt($deleteDeadline)) {
            return back()->with('error', 'Transaksi hanya bisa dihapus dalam 1 jam setelah dibuat.');
        }

        DB::beginTransaction();
        // try {
        //     $customerName = $transaction->customer_name;
        //     $transaction->delete();

        //     DB::commit();
        //     return redirect()->route('transactions.index')
        //         ->with('success', 'Transaction for '.$customerName.' has been deleted successfully!');
        // } catch (\Throwable $th) {
        //     DB::rollBack();
        //     return back()->with('error', $th->getMessage());
        // }

                try {
            // Hanya boleh delete jika status belum completed
            if ($transaction->status === 'completed') {
                return back()->with('error', 'Transaksi yang sudah completed tidak bisa dihapus.');
            }

            $transaction->delete();

            DB::commit();
            return redirect()->route('transactions.index')
                ->with('success', 'Transaksi berhasil dihapus!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }

    }
}
