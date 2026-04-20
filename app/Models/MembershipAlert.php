<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'alert_type',
        'alert_date',
        'membership_expiry_date',
        'is_sent',
        'sent_at',
        'message',
    ];

    protected $casts = [
        'alert_date' => 'date',
        'membership_expiry_date' => 'date',
        'is_sent' => 'boolean',
        'sent_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function markAsSent()
    {
        $this->update([
            'is_sent' => true,
            'sent_at' => now(),
        ]);
    }

    public function getTypeLabelAttribute()
    {
        return match($this->alert_type) {
            'expiry_warning' => 'Expiry Warning',
            'expired' => 'Expired',
            'renewal_reminder' => 'Renewal Reminder',
            default => ucfirst(str_replace('_', ' ', $this->alert_type))
        };
    }
}
