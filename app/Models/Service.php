<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceFactory> */
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'price', 'duration', 'pricing_levels', 'peak_pricing_enabled', 'peak_price', 'peak_start', 'peak_end', 'image', 'is_popular'];

    protected $casts = [
        'pricing_levels' => 'array',
        'peak_pricing_enabled' => 'boolean',
        'peak_price' => 'decimal:2',
        'duration' => 'integer',
        'is_popular' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function serviceSupplies()
    {
        return $this->hasMany(ServiceSupply::class);
    }

    public function supplies()
    {
        return $this->belongsToMany(Product::class, 'service_supplies')
                    ->withPivot('quantity_per_service', 'is_active')
                    ->wherePivot('is_active', true);
    }
}
