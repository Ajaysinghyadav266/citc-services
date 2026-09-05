<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebHostingRequest extends Model
{
    protected $table = 'web_hosting_requests';

    protected $fillable = [
        'institute_email',
        'department_name',
        'owner_name',
        'mobile_number',
        'employee_category',
        'approver_email',
        'approver_name',
        'approver_designation',
        'approver_department',
        'website_name',
        'suggested_domain_name',
        'operating_system',
        'purpose',
        'comment',
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