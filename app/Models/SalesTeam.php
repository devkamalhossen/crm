<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesTeam extends Model
{
    protected $fillable = [
        'user_id',
        'employee_id',
        'name',
        'designation',
        'mobile_number',
        'email',
        'joining_date',
        'status',
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clientServices()
    {
        return $this->belongsToMany(ClientService::class, 'sales_team_client_service');
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }


}
