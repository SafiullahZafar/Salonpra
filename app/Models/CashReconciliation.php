<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashReconciliation extends Model
{
    /** @use HasFactory<\Database\Factories\CashReconciliationFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id', 'date', 'opening_balance', 'expected_cash', 
        'actual_cash', 'difference', 'notes', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
