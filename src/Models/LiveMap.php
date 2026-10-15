<?php

namespace Azuriom\Plugin\LiveMap\Models;

use Azuriom\Models\Server;
use Azuriom\Models\Traits\Loggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string $url
 * @property int|null $server_id
 * @property string|null $icon
 * @property int $position
 * @property bool $is_enabled
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Azuriom\Models\Server|null $server
 *
 * @method static \Illuminate\Database\Eloquent\Builder enabled()
 */
class LiveMap extends Model
{
    use Loggable;

    public const CACHE_KEY = 'live-map.maps';

    /**
     * Supported map renderers, with their default icon.
     *
     * @var array<string, string>
     */
    public const TYPES = [
        'bluemap' => 'bi bi-box',
        'dynmap' => 'bi bi-map',
        'squaremap' => 'bi bi-grid-3x3',
        'pl3xmap' => 'bi bi-globe2',
        'custom' => 'bi bi-link-45deg',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'live_map_maps';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'type', 'url', 'server_id', 'icon', 'position', 'is_enabled',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'position' => 'integer',
        'is_enabled' => 'boolean',
    ];

    protected static function booted(): void
    {
        foreach (['saved', 'deleted'] as $event) {
            static::registerModelEvent($event, fn () => Cache::forget(self::CACHE_KEY));
        }
    }

    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function iconClass(): string
    {
        return $this->icon ?: (self::TYPES[$this->type] ?? self::TYPES['custom']);
    }

    /**
     * Scope a query to only include enabled maps.
     */
    public function scopeEnabled(Builder $query): void
    {
        $query->where('is_enabled', true);
    }

    /**
     * Get the enabled maps, ordered by position (cached).
     *
     * @return \Illuminate\Support\Collection<int, self>
     */
    public static function enabledMaps(): Collection
    {
        return Cache::remember(self::CACHE_KEY, now()->addHour(), function () {
            return self::enabled()->orderBy('position')->orderBy('id')->get();
        });
    }

    /**
     * Get the map displayed by default.
     */
    public static function defaultMap(): ?self
    {
        $maps = self::enabledMaps();
        $defaultId = (int) setting('live-map.default_map');

        return $maps->firstWhere('id', $defaultId) ?? $maps->first();
    }
}
