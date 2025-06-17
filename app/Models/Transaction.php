<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'transaction_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_name',
        'date',
        'total_price',
        'payment_method',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date',
        'total_price' => 'decimal:2',
    ];

    /**
     * Get the formatted total price with currency symbol.
     *
     * @return string
     */
    public function getFormattedTotalPriceAttribute()
    {
        return 'Rp ' . number_format($this->total_price, 2, ',', '.');
    }

    // Tambahkan method ini di Transaction model
    public function details()
    {
        return $this->hasMany(DetailTransaction::class, 'transaction_id');
    }

}
