<?php

namespace App\Http\Controllers;

use App\Models\CategoryMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = CategoryMenu::when(request()->search, function ($query) {
            $query->where('category_name', 'like', '%' . request()->search . '%');
        })->orderBy('created_at', 'desc')->paginate(10);

        return view('category_menus.index', compact('categories'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('category_menus.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:50|unique:category_menus',
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $category = CategoryMenu::create([
                'category_name' => $request->category_name,
                'description' => $request->description
            ]);

            DB::commit();
            return redirect()->route('category_menus.index')
                ->with('success', 'Category '.$category->category_name.' has been added successfully!');
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
    public function edit(CategoryMenu $category_menu)
    {
        return view('category_menus.edit', compact('category_menu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'required|string|max:50|unique:category_menus,category_name,'.$id.',category_id',
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $category = CategoryMenu::findOrFail($id);
            $category->update([
                'category_name' => $request->category_name,
                'description' => $request->description
            ]);

            DB::commit();
            return redirect()->route('category_menus.index')
                ->with('success', 'Category '.$category->category_name.' has been updated successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoryMenu $category_menu)
    {
        DB::beginTransaction();
        try {
            if ($category_menu) {
                $category_name = $category_menu->category_name;
                $category_menu->delete();

                DB::commit();
                return redirect()->route('category_menus.index')
                    ->with('success', 'Category '.$category_name.' has been deleted successfully!');
            } else {
                return back()->with('error', 'Category not found!');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }
}
