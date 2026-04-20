<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceItemFactory> */
    use HasFactory;

    protected $fillable = [
        'invoice_id', 'itemizable_id', 'itemizable_type', 
        'quantity', 'price', 'subtotal'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function itemizable()
    {
        return $this->morphTo();
    }
}
