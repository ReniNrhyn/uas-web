<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockPurchase extends Model
{
    use HasFactory;

    protected $primaryKey = 'purchase_id';
    protected $fillable = [
        'stock_id',
        'quantity',
        'unit_price',
        'date',
        'supplier'
    ];

    public function material()
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }
}
