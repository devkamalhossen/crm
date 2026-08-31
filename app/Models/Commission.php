<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_team_id',
        'client_service_id',
        'commission_rule_id',
        'commission_amount',
        'status',
        'earned_at',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'commission_amount' => 'decimal:2',
        'earned_at' => 'date',
        'paid_at' => 'date',
    ];

    public function salesTeam()
    {
        return $this->belongsTo(SalesTeam::class);
    }

    public function clientService()
    {
        return $this->belongsTo(ClientService::class);
    }

    public function commissionRule()
    {
        return $this->belongsTo(CommissionRule::class);
    }
}
