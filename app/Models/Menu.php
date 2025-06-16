<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Menu extends Model
{
    use HasFactory;

    protected $primaryKey = 'menu_id';
    protected $fillable = [
        'menu_name',
        'category_id',
        'price',
        'description',
        'stock',
        'is_available'
    ];

    // Relasi ke CategoryMenu (Menu milik 1 Kategori)
    public function category(): BelongsTo
    {
        return $this->belongsTo(CategoryMenu::class, 'category_id');
    }
}
