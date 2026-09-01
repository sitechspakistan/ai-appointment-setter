<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CallLog extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'appointment_id',
        'vapi_call_id',
        'status',
        'outcome',
        'recording_url',
        'duration_seconds',
        'started_at',
        'ended_at',
    ];

    protected function casts(): array
    {
        return [
            'duration_seconds' => 'integer',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }
}
