<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reminder extends Model
{
    use BelongsToTenant;

    public const CHANNEL_WHATSAPP = 'whatsapp';
    public const CHANNEL_VOICE = 'voice';

    public const STATUS_QUEUED = 'queued';
    public const STATUS_SENT = 'sent';
    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'tenant_id',
        'appointment_id',
        'channel',
        'status',
        'scheduled_for',
        'sent_at',
        'outcome',
        'provider_message_id',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_for' => 'datetime',
            'sent_at' => 'datetime',
        ];
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function scopeSent(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_SENT);
    }

    public function scopeQueued(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_QUEUED);
    }

    public function channelLabel(): string
    {
        return $this->channel === self::CHANNEL_VOICE ? 'Voice call' : 'WhatsApp';
    }

    public function outcomeLabel(): string
    {
        return match ($this->outcome) {
            'confirmed' => 'Confirmed',
            'declined' => 'Declined',
            'no_reply' => 'No reply',
            default => 'Pending',
        };
    }
}
