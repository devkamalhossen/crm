<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
     use HasFactory;

    protected $fillable = [
        'user_id',
        'client_service_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'subtotal',
        'discount',
        'tax',
        'total_amount',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'invoice_date' => 'date',
            'due_date' => 'date',

            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'tax' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Client
    |--------------------------------------------------------------------------
    */

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Client Service
    |--------------------------------------------------------------------------
    */

    public function clientService(): BelongsTo
    {
        return $this->belongsTo(ClientService::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Invoice Items
    |--------------------------------------------------------------------------
    */

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Payments
    |--------------------------------------------------------------------------
    */

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Paid Amount
    |--------------------------------------------------------------------------
    */

    public function getPaidAmountAttribute(): float
    {
        return (float) $this->payments()
            ->where('status', 'completed')
            ->sum('amount');
    }

    /*
    |--------------------------------------------------------------------------
    | Due Amount
    |--------------------------------------------------------------------------
    */

    public function getDueAmountAttribute(): float
    {
        return max(
            0,
            (float) $this->total_amount - $this->paid_amount
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Payment Status
    |--------------------------------------------------------------------------
    */

    public function getPaymentStatusAttribute(): string
    {
        $paid = $this->paid_amount;
        $total = (float) $this->total_amount;

        if ($this->status === 'cancelled') {
            return 'cancelled';
        }

        if ($paid >= $total) {
            return 'paid';
        }

        if ($paid > 0) {
            return 'partial';
        }

        if ($this->due_date && $this->due_date->isPast()) {
            return 'overdue';
        }

        return 'unpaid';
    }
}
