<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceSupply extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceSupplyFactory> */
    use HasFactory;

    protected $fillable = [
        'service_id',
        'product_id',
        'quantity_per_service',
        'is_active'
    ];

    protected $casts = [
        'quantity_per_service' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
