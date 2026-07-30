<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternetAccessRequest extends Model
{
    protected $fillable = [
        'name',
        'roll_no',
        'email',
        'phone',
        'approver_email',
        'approver_name',
        'approver_designation',
        'approver_department',
        'device_type',
        'operating_system',
        'mac_address',
        'connection_duration',
        'status',
        // Approval workflow
        'approval_status',
        'approver1_email',
        'approver1_name',
        'approved_by_1_at',
        'approver2_email',
        'approver2_name',
        'approved_by_2_at',
        'citc_completed_at',
        'citc_completed_by',
        'rejected_by',
        'rejected_by_level',
        'rejection_reason',
        'rejected_at',
    ];

    protected $casts = [
        'approved_by_1_at' => 'datetime',
        'approved_by_2_at' => 'datetime',
        'citc_completed_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];
}

