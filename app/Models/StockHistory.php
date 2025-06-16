<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    use HasFactory;

    protected $primaryKey = 'history_id';
    protected $fillable = [
        'stock_id',
        'menu_id',
        'quantity',
        'type',
        'date'
    ];

    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
