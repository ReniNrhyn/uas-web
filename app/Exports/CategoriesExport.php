<?php

namespace App\Exports;

use App\Models\CategoryMenu;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CategoriesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return CategoryMenu::select('category_name', 'description')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Category Name',
            'Description'
        ];
    }
}
