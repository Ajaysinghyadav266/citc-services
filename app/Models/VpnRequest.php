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
    ];
}
