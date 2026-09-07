<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SmsReminderSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'trigger_type',
        'days',
        'message',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'days' => 'integer',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | SMS Logs
    |--------------------------------------------------------------------------
    */

    public function smsLogs(): HasMany
    {
        return $this->hasMany(SmsLog::class);
    }
}
