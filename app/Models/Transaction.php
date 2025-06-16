<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'transaction_id';
    protected $fillable = [
        'customer_name',
        'date',
        'total_price',
        'payment_method'
    ];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id');
    }
}
