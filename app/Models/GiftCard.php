<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_number',
        'pin',
        'initial_balance',
        'current_balance',
        'customer_id',
        'issued_by',
        'issued_date',
        'expiry_date',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'initial_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'issued_date' => 'date',
        'expiry_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function isValid()
    {
        if (!$this->is_active) return false;
        if ($this->current_balance <= 0) return false;
        if ($this->expiry_date && $this->expiry_date->isPast()) return false;

        return true;
    }

    public function deductAmount($amount)
    {
        if ($this->current_balance >= $amount) {
            $this->decrement('current_balance', $amount);
            return true;
        }
        return false;
    }

    public function addAmount($amount)
    {
        $this->increment('current_balance', $amount);
    }

    public function getStatusAttribute()
    {
        if (!$this->is_active) return 'inactive';
        if ($this->current_balance <= 0) return 'used';
        if ($this->expiry_date && $this->expiry_date->isPast()) return 'expired';
        return 'active';
    }
}
