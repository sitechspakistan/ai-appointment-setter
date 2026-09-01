<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Tenant extends Model
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_TRIAL = 'trial';
    public const STATUS_PAUSED = 'paused';

    protected $fillable = [
        'business_name',
        'booking_slug',
        'industry',
        'status',
        'location',
        'timezone',
        'contact_phone',
        'plan',
        'seats',
        'monthly_amount',
        'trial_ends_at',
        'vapi_phone_number_id',
        'vapi_assistant_id',
        'whatsapp_phone_number_id',
        'whatsapp_template_name',
        'whatsapp_reminder_message',
        'confirmation_call_script',
    ];

    protected function casts(): array
    {
        return [
            'seats' => 'integer',
            'monthly_amount' => 'decimal:2',
            'trial_ends_at' => 'date',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function businessHours(): HasMany
    {
        return $this->hasMany(BusinessHour::class);
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(Reminder::class);
    }

    public function callLogs(): HasMany
    {
        return $this->hasMany(CallLog::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /** Path portion of the public booking link, e.g. "book/sarahshvac". */
    public function bookingPath(): string
    {
        return 'book/'.$this->booking_slug;
    }

    /** Full public booking URL shown in the dashboard / embed screens. */
    public function bookingUrl(): string
    {
        $domain = Setting::get('booking_domain');

        return $domain
            ? 'https://'.rtrim($domain, '/').'/'.$this->bookingPath()
            : url($this->bookingPath());
    }

    public function embedSnippet(): string
    {
        $url = $this->bookingUrl();
        $title = 'Book an appointment — '.$this->business_name;

        return <<<HTML
        <iframe
          src="{$url}"
          width="100%" height="720"
          style="border:0;border-radius:16px"
          title="{$title}"
          loading="lazy"></iframe>
        HTML;
    }

    public function initials(): string
    {
        $parts = preg_split('/\s+/', trim((string) $this->business_name)) ?: [];
        $letters = collect($parts)->filter()->map(fn ($p) => Str::substr($p, 0, 1));

        return Str::upper($letters->take(2)->implode('')) ?: 'T';
    }

    public function isPaused(): bool
    {
        return $this->status === self::STATUS_PAUSED;
    }
}
