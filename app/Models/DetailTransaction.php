<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'detail_id';
    protected $table = 'detail_transactions';

    protected $fillable = [
        'transaction_id',
        'menu_id',
        'quantity',
        'subtotal'
    ];

    /**
     * Get the transaction that owns the detail transaction.
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    /**
     * Get the menu associated with the detail transaction.
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    /**
     * Calculate subtotal automatically based on menu price and quantity
     */
    public static function calculateSubtotal($menuId, $quantity)
    {
        $menu = Menu::findOrFail($menuId);
        return $menu->price * $quantity;
    }
}
