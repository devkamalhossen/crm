<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'user_id',
        'payment_reference',
        'payment_date',
        'amount',
        'payment_method',
        'status',
        'transaction_id',
        'notes',
        'received_by',
    ];

    protected function casts(): array
    {
        return [
            'payment_date' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Invoice
    |--------------------------------------------------------------------------
    */

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
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
    | Received By
    |--------------------------------------------------------------------------
    */

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
