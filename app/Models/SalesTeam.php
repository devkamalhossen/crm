<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesTeam extends Model
{
    protected $fillable = [
        'employee_id',
        'name',
        'designation',
        'mobile_number',
        'email',
        'joining_date',
        'status',
    ];
}
