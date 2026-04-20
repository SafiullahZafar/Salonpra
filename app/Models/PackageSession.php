<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'package_id',
        'total_sessions',
        'used_sessions',
        'remaining_sessions',
        'purchase_date',
        'expiry_date',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'total_sessions' => 'integer',
        'used_sessions' => 'integer',
        'remaining_sessions' => 'integer',
        'purchase_date' => 'date',
        'expiry_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function package()
    {
        return $this->belongsTo(ServicePackage::class, 'package_id');
    }

    public function useSession()
    {
        if ($this->remaining_sessions > 0) {
            $this->increment('used_sessions');
            $this->decrement('remaining_sessions');
            return true;
        }
        return false;
    }

    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function isActive()
    {
        return $this->is_active && !$this->isExpired() && $this->remaining_sessions > 0;
    }

    public function getStatusAttribute()
    {
        if (!$this->is_active) return 'inactive';
        if ($this->isExpired()) return 'expired';
        if ($this->remaining_sessions <= 0) return 'completed';
        return 'active';
    }
}
