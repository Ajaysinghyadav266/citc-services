<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approver extends Model
{
    //
    protected $fillable = [
    'name',
    'email',
    'designation',
    'last_login'
];
}
