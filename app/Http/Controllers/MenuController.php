<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\CategoryMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $menus = Menu::with('category')
            ->when($search, function($query) use ($search) {
                return $query->where('menu_name', 'like', "%{$search}%")
                    ->orWhereHas('category', function($q) use ($search) {
                        $q->where('category_name', 'like', "%{$search}%");
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('menus.index', compact('menus', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = CategoryMenu::orderBy('category_name')->get();
        return view('menus.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'menu_name' => 'required|string|max:100',
            'category_id' => 'required|exists:category_menus,category_id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'is_available' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $menuData = $request->all();
            $menuData['is_available'] = $request->has('is_available') ? true : false;

            Menu::create($menuData);
            DB::commit();
            return redirect()->route('menus.index')->with('success', 'Menu created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create menu. Error: '.$e->getMessage());
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
    public function edit(string $id)
    {
        $menu = Menu::findOrFail($id);
        $categories = CategoryMenu::orderBy('category_name')->get();
        return view('menus.edit', compact('menu', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'menu_name' => 'required|string|max:100',
            'category_id' => 'required|exists:category_menus,category_id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'is_available' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $menu = Menu::findOrFail($id);
            $menuData = $request->all();
            $menuData['is_available'] = $request->has('is_available') ? true : false;

            $menu->update($menuData);
            DB::commit();
            return redirect()->route('menus.index')->with('success', 'Menu updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update menu. Error: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $menu = Menu::findOrFail($id);
            $menu->delete();
            DB::commit();
            return redirect()->route('menus.index')->with('success', 'Menu deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete menu. Error: '.$e->getMessage());
        }
    }
}
