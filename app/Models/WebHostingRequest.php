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
        'website_name',
        'suggested_domain_name',
        'operating_system',
        'purpose',
        'comment',
    ];
}