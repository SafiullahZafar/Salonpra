<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FbrLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'invoice_no',
        'payload',
        'status',
        'response'
    ];
    protected $casts = [
        // Removing standard 'array' cast to handle encryption manually
    ];

    public function getPayloadAttribute($value)
    {
        if (empty($value)) return [];
        try {
            // First decode the JSON string literal to get the raw encrypted text
            $unwrapped = json_decode($value, true);
            if (is_array($unwrapped)) return $unwrapped; // Legacy structure fallback

            $decrypted = \Illuminate\Support\Facades\Crypt::decryptString($unwrapped);
            return json_decode($decrypted, true);
        } catch (\Exception $e) {
            return json_decode($value, true) ?: []; // Final fallback
        }
    }

    public function setPayloadAttribute($value)
    {
        // Encrypt the JSON data, then JSON-encode the resulting string literal for MySQL compatibility
        $encrypted = \Illuminate\Support\Facades\Crypt::encryptString(json_encode($value));
        $this->attributes['payload'] = json_encode($encrypted);
    }

    public function getResponseAttribute($value)
    {
        if (empty($value)) return [];
        try {
            $unwrapped = json_decode($value, true);
            if (is_array($unwrapped)) return $unwrapped; // Legacy structure fallback

            $decrypted = \Illuminate\Support\Facades\Crypt::decryptString($unwrapped);
            return json_decode($decrypted, true);
        } catch (\Exception $e) {
            return json_decode($value, true) ?: []; // Final fallback
        }
    }

    public function setResponseAttribute($value)
    {
        $encrypted = \Illuminate\Support\Facades\Crypt::encryptString(json_encode($value));
        $this->attributes['response'] = json_encode($encrypted);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
