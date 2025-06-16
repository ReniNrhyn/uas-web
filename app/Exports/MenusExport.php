<?php

namespace App\Exports;

use App\Models\Menu;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MenusExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Menu::with('category')
            ->select('menu_name', 'category_id', 'price', 'stock', 'is_available', 'description')
            ->get()
            ->map(function ($menu) {
                return [
                    'Menu Name' => $menu->menu_name,
                    'Category' => $menu->category->category_name,
                    'Price' => $menu->price,
                    'Stock' => $menu->stock,
                    'Available' => $menu->is_available ? 'Yes' : 'No',
                    'Description' => $menu->description
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Menu Name',
            'Category',
            'Price',
            'Stock',
            'Available',
            'Description'
        ];
    }
}
