<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Appointment;
use App\Models\Invoice;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'birthday',
        'preferences',
        'membership_type',
        'membership_expires',
        'prepaid_credit',
        'notes',
        'last_visit_at',
    ];

    protected $casts = [
        'birthday' => 'date',
        'membership_expires' => 'date',
        'last_visit_at' => 'datetime',
        'prepaid_credit' => 'decimal:2',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function getMembershipStatusAttribute()
    {
        if (!$this->membership_type) {
            return 'None';
        }

        if ($this->membership_expires && $this->membership_expires->isFuture()) {
            return 'Active';
        }

        return 'Expired';
    }

    public function getNameAttribute($value)
    {
        if (empty($value)) return $value;
        try {
            return \Illuminate\Support\Facades\Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = empty($value) ? $value : \Illuminate\Support\Facades\Crypt::encryptString($value);
    }

    public function getPhoneAttribute($value)
    {
        if (empty($value)) return $value;
        try {
            return \Illuminate\Support\Facades\Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = empty($value) ? $value : \Illuminate\Support\Facades\Crypt::encryptString($value);
    }

    public function getEmailAttribute($value)
    {
        if (empty($value)) return $value;
        try {
            return \Illuminate\Support\Facades\Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = empty($value) ? $value : \Illuminate\Support\Facades\Crypt::encryptString($value);
    }
}
