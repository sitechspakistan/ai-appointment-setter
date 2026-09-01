<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class BusinessHour extends Model
{
    use BelongsToTenant;

    /** 0 = Sunday … 6 = Saturday */
    public const DAYS = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    protected $fillable = [
        'tenant_id',
        'day_of_week',
        'is_closed',
        'opens_at',
        'closes_at',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'is_closed' => 'boolean',
            'day_of_week' => 'integer',
        ];
    }

    public function dayName(): string
    {
        return self::DAYS[$this->day_of_week] ?? '';
    }

    public function rangeLabel(): string
    {
        if ($this->is_closed) {
            return $this->note ?: 'Closed';
        }

        return Carbon::parse($this->opens_at)->format('g:i A')
            .' – '.Carbon::parse($this->closes_at)->format('g:i A');
    }
}
