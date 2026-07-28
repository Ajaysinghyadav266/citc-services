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
    ];

    protected $casts = [
        'vm_expiry_date' => 'date',
        'cpu_cores'      => 'integer',
        'ram_gb'         => 'integer',
        'hard_disk_gb'   => 'integer',
    ];
}