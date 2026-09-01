<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Appointment extends Model
{
    use BelongsToTenant;

    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_DECLINED = 'declined';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_NO_SHOW = 'no_show';

    /** Statuses a Tenant Admin can filter by on the Appointments screen. */
    public const FILTERABLE = ['confirmed', 'pending', 'declined'];

    protected $fillable = [
        'tenant_id',
        'service_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'service_name',
        'notes',
        'appointment_date',
        'appointment_time',
        'status',
        'source',
        'confirmed_at',
        'confirmation_method',
    ];

    protected function casts(): array
    {
        return [
            'appointment_date' => 'date',
            'confirmed_at' => 'datetime',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(Reminder::class);
    }

    public function callLogs(): HasMany
    {
        return $this->hasMany(CallLog::class);
    }

    /* ---------------- scopes ---------------- */

    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        return $status && $status !== 'all'
            ? $query->where('status', $status)
            : $query;
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('appointment_date', '>=', now()->toDateString())
            ->orderBy('appointment_date')
            ->orderBy('appointment_time');
    }

    public function scopeForWeek(Builder $query, Carbon $start, Carbon $end): Builder
    {
        return $query->whereBetween('appointment_date', [$start->toDateString(), $end->toDateString()]);
    }

    /* ---------------- helpers ---------------- */

    public function timeLabel(): string
    {
        return Carbon::parse($this->appointment_time)->format('g:i A');
    }

    public function dateLabel(): string
    {
        return $this->appointment_date->format('D, M j');
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_NO_SHOW => 'No-Show',
            default => ucfirst($this->status),
        };
    }
}
