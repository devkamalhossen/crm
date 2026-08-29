<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClientService extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sales_team_id',
        'service_type',
        'payment_type',
        'total_amount',
        'advance_amount',
        'installment_months',
        'start_date',
        'end_date',
        'project_status',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'advance_amount' => 'decimal:2',
            'installment_months' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'client_service_id');
    }

    public function salesperson()
    {
        return $this->belongsTo(SalesTeam::class, 'sales_team_id');
    }

    public function salesTeams()
    {
        return $this->belongsToMany(SalesTeam::class, 'sales_team_client_service');
    }
}
