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
        'reason',
        'resources',
        'faculty_name',
        'faculty_email',
        'department'
    ];
}
