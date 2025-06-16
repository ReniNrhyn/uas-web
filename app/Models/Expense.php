<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $primaryKey = 'expense_id';
    protected $fillable = [
        'expense_category_id',
        'quantity',
        'description',
        'date'
    ];

    public function category()
    {
        return $this->belongsTo(ExpenditureCategory::class, 'expense_category_id');
    }
}
