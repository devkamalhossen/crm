<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProjectReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'report_type',
        'report_date',
        'due_date',
        'status',
        'report_link',
        'completed_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'report_date' => 'date',
            'due_date' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * Project relationship
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function meeting(): HasOne
    {
        return $this->hasOne(ProjectMeeting::class);
    }
}
