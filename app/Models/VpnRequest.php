<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VpnRequest extends Model
{
    protected $fillable = [
        'name',
        'email',
        'contact',
        'operating_system',
        'start_date',
        'end_date',
        'purpose',
        'resources',
        'approver_email',
        'approver_name',
        'approver_designation',
        'approver_department',
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

