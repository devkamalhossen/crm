<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsLog extends Model
{
     use HasFactory;

    protected $fillable = [
        'invoice_id',
        'user_id',
        'sms_reminder_setting_id',
        'phone',
        'trigger_type',
        'message',
        'status',
        'sent_at',
        'provider_response',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
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
    | Reminder Setting
    |--------------------------------------------------------------------------
    */

    public function reminderSetting(): BelongsTo
    {
        return $this->belongsTo(
            SmsReminderSetting::class,
            'sms_reminder_setting_id'
        );
    }
}
