<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_type',
        'commission_type',
        'commission_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'commission_amount' => 'decimal:2',
    ];

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }
}
