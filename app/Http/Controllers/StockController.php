<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stocks = Stock::when(request()->search, function ($query) {
            $query->where('stock_name', 'like', '%' . request()->search . '%');
        })->paginate(10);

        return view('stocks.index', compact('stocks'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('stocks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'stock_name' => 'required|string|max:100',
            'unit' => 'required|string|max:20',
            'minimum_stock' => 'required|integer|min:0'
        ]);

        try {
            $stock = Stock::create([
                'stock_name' => $request->stock_name,
                'unit' => $request->unit,
                'minimum_stock' => $request->minimum_stock
            ]);

            return redirect()->route('stocks.index')
                ->with('success', 'Stock "'.$stock->stock_name.'" has been added successfully!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Tidak digunakan, bisa diimplementasikan jika diperlukan
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stock $stock)
    {
        return view('stocks.edit', compact('stock'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stock $stock)
    {
        $request->validate([
            'stock_name' => 'required|string|max:100',
            'unit' => 'required|string|max:20',
            'minimum_stock' => 'required|integer|min:0'
        ]);

        try {
            $stock->update([
                'stock_name' => $request->stock_name,
                'unit' => $request->unit,
                'minimum_stock' => $request->minimum_stock
            ]);

            return redirect()->route('stocks.index')
                ->with('success', 'Stock "'.$stock->stock_name.'" has been updated successfully!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stock $stock)
    {
        try {
            $stockName = $stock->stock_name;
            $stock->delete();

            return redirect()->route('stocks.index')
                ->with('success', 'Stock "'.$stockName.'" has been deleted successfully!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
