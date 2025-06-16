<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryMenu extends Model
{
    use HasFactory;

    protected $primaryKey = 'category_id';
    protected $fillable = ['category_name', 'description'];

    // Relasi ke Menu (1 Kategori punya banyak Menu)
    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class, 'category_id');
    }
}
