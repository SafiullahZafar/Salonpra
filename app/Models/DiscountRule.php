<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'value',
        'buy_quantity',
        'get_quantity',
        'is_active',
        'valid_from',
        'valid_until',
        'conditions',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'buy_quantity' => 'integer',
        'get_quantity' => 'integer',
        'is_active' => 'boolean',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'conditions' => 'array',
    ];

    public function isValid()
    {
        $now = now()->toDateString();
        return $this->is_active &&
               (!$this->valid_from || $this->valid_from <= $now) &&
               (!$this->valid_until || $this->valid_until >= $now);
    }
}
