<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VmRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'requested_by',
        'institute_email',
        'department_name',
        'owner_name',
        'mobile_number',
        'employee_category',
        'approver_email',
        'approver_name',
        'approver_designation',
        'approver_department',
        'operating_system',
        'vm_expiry_date',
        'os_type',
        'purpose_usage',
        'cpu_cores',
        'ram_gb',
        'justification',
        'hard_disk_gb',
        'license_type',
        'sub_domain',
        'software_list',
        'ssl_configuration',
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
        'vm_expiry_date'   => 'date',
        'cpu_cores'        => 'integer',
        'ram_gb'           => 'integer',
        'hard_disk_gb'     => 'integer',
        'approved_by_1_at' => 'datetime',
        'approved_by_2_at' => 'datetime',
        'citc_completed_at'=> 'datetime',
        'rejected_at'      => 'datetime',
    ];
}