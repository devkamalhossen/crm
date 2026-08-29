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
        'sales_team_id',
        'client_service_id',
    ];

    public function clientServices()
    {
        return $this->belongsToMany(ClientService::class, 'sales_team_client_service');
    }
}
