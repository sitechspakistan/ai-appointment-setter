<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    public const STATUS_PAID = 'paid';
    public const STATUS_PAST_DUE = 'past_due';
    public const STATUS_TRIAL = 'trial';
    public const STATUS_OPEN = 'open';

    protected $fillable = [
        'tenant_id',
        'number',
        'plan',
        'seats',
        'amount',
        'status',
        'period_start',
        'period_end',
        'issued_on',
        'due_on',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'seats' => 'integer',
            'amount' => 'decimal:2',
            'period_start' => 'date',
            'period_end' => 'date',
            'issued_on' => 'date',
            'due_on' => 'date',
            'paid_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_PAID => 'Paid',
            self::STATUS_PAST_DUE => 'Past due',
            self::STATUS_TRIAL => 'Trial',
            default => 'Open',
        };
    }
}
