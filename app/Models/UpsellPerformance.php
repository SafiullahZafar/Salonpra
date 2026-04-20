<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpsellPerformance extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'total_upsells',
        'upsell_revenue',
        'conversion_rate',
        'average_upsell_value',
        'last_upsell_date',
    ];

    protected $casts = [
        'upsell_revenue' => 'decimal:2',
        'conversion_rate' => 'decimal:2',
        'average_upsell_value' => 'decimal:2',
        'last_upsell_date' => 'datetime',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
