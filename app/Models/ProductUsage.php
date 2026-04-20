<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'service_id',
        'invoice_id',
        'quantity_used',
        'usage_date',
        'notes'
    ];

    protected $casts = [
        'usage_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
