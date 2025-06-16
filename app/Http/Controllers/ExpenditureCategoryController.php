<?php

namespace App\Http\Controllers;

use App\Models\ExpenditureCategory;
use Illuminate\Http\Request;

class ExpenditureCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = ExpenditureCategory::when(request()->search, function ($query) {
            $query->where('category_name', 'like', '%' . request()->search . '%');
        })->paginate(10);

        return view('expenditure-categories.index', compact('categories'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('expenditure-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|unique:expenditure_categories',
        ]);

        try {
            $category = ExpenditureCategory::create([
                'category_name' => $request->category_name,
            ]);

            return redirect()->route('expenditure-categories.index')
                ->with('success', 'Category "'.$category->category_name.'" has been added successfully!');
        } catch (\Throwable $th) {
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
    public function edit(ExpenditureCategory $expenditureCategory)
    {
        return view('expenditure-categories.edit', compact('expenditureCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'required|string|unique:expenditure_categories,category_name,'.$id.',category_expenditure_id',
        ]);

        try {
            $category = ExpenditureCategory::find($id);
            $category->category_name = $request->category_name;
            $category->save();

            return redirect()->route('expenditure-categories.index')
                ->with('success', 'Category "'.$category->category_name.'" has been updated successfully!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpenditureCategory $expenditureCategory)
    {
        if ($expenditureCategory) {
            $categoryName = $expenditureCategory->category_name;
            $expenditureCategory->delete();

            return redirect()->route('expenditure-categories.index')
                ->with('success', 'Category "'.$categoryName.'" has been deleted successfully!');
        } else {
            return back()->with('error', 'Category not found!');
        }
    }
}
