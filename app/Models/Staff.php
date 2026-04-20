<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'position',
        'hourly_rate',
        'hiring_date',
        'status',
        'bio',
    ];

    protected $casts = [
        'hourly_rate' => 'decimal:2',
        'hiring_date' => 'date',
        'status' => 'boolean',
    ];

    public function attendances()
    {
        return $this->hasMany(StaffAttendance::class);
    }

    public function shifts()
    {
        return $this->hasMany(StaffShift::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function upsellPerformance()
    {
        return $this->hasOne(UpsellPerformance::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'staff_service');
    }
}
