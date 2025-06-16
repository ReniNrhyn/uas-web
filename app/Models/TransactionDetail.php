<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'detail_id';
    protected $fillable = [
        'transaction_id',
        'menu_id',
        'quantity',
        'subtotal'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
