<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectMeeting extends Model
{
     use HasFactory;

    protected $fillable = [
        'project_id',
        'project_report_id',
        'meeting_date',
        'status',
        'title',
        'agenda',
        'notes',
        'completed_at',
        'reminder_at',
        'reminder_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'meeting_date' => 'date',
            'completed_at' => 'datetime',
            'reminder_at' => 'datetime',
            'reminder_sent_at' => 'datetime',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function projectReport(): BelongsTo
    {
        return $this->belongsTo(ProjectReport::class);
    }
}
