<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use BelongsToTenant;

    /**
     * Curated Font Awesome 6 (free, solid) icons offered in the
     * booking-settings icon picker. Key = icon name, value = label.
     */
    public const ICONS = [
        'snowflake' => 'Snowflake', 'fire' => 'Fire', 'temperature-half' => 'Thermostat',
        'fan' => 'Fan', 'wind' => 'Air / duct', 'plug' => 'Electrical', 'bolt' => 'Power',
        'screwdriver-wrench' => 'Repair', 'wrench' => 'Wrench', 'toolbox' => 'Toolbox',
        'gears' => 'Mechanical', 'hammer' => 'Hammer', 'paint-roller' => 'Painting',
        'faucet-drip' => 'Plumbing', 'droplet' => 'Water', 'water-ladder' => 'Pool',
        'hot-tub-person' => 'Spa / hot tub', 'broom' => 'Cleaning', 'soap' => 'Wash',
        'spray-can-sparkles' => 'Detailing', 'house' => 'Home', 'house-chimney' => 'Roofing',
        'helmet-safety' => 'Construction', 'tooth' => 'Dental', 'teeth' => 'Teeth',
        'syringe' => 'Injectable', 'hand-sparkles' => 'Beauty', 'scissors' => 'Salon',
        'leaf' => 'Landscaping', 'seedling' => 'Garden', 'bug' => 'Pest control',
        'car' => 'Auto', 'truck-fast' => 'Dispatch', 'phone-volume' => 'Call-out',
        'calendar-check' => 'Booking', 'clipboard-list' => 'Quote / estimate', 'star' => 'General',
    ];

    protected $fillable = [
        'tenant_id',
        'name',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /** Full Font Awesome class for the stored icon name, with a safe fallback. */
    public function iconClass(): string
    {
        $name = $this->icon && array_key_exists($this->icon, self::ICONS) ? $this->icon : 'wrench';

        return 'fa-solid fa-'.$name;
    }
}
