<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'minimum_purchase',
        'usage_limit',
        'usage_limit_per_customer',
        'used_count',
        'is_active',
        'valid_from',
        'valid_until',
        'applicable_services',
        'applicable_products',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'minimum_purchase' => 'decimal:2',
        'usage_limit' => 'integer',
        'usage_limit_per_customer' => 'integer',
        'used_count' => 'integer',
        'is_active' => 'boolean',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'applicable_services' => 'array',
        'applicable_products' => 'array',
    ];

    public function isValid($customerId = null, $totalAmount = 0)
    {
        $now = now()->toDateString();

        // Check if coupon is active and within valid dates
        if (!$this->is_active) return false;
        if ($this->valid_from && $this->valid_from > $now) return false;
        if ($this->valid_until && $this->valid_until < $now) return false;

        // Check usage limits
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;

        // Check minimum purchase
        if ($this->minimum_purchase && $totalAmount < $this->minimum_purchase) return false;

        return true;
    }

    public function canBeUsedByCustomer($customerId, $usageCount = 1)
    {
        if (!$this->usage_limit_per_customer) return true;

        // This would need to be implemented with a coupon_usage table to track per-customer usage
        // For now, return true as we don't have that table yet
        return true;
    }

    public function incrementUsage()
    {
        $this->increment('used_count');
    }
}
