<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Screen\AsSource;

/**
 * Секция здания
 */
final class Section extends Model
{
    use AsSource;
    use HasUlids;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'building_id',
        'name',
        'floors_count',
        'sort_order',
    ];

    /**
     * Секция принадлежит зданию.
     *
     * @return BelongsTo
     */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * В секции могут быть этажи.
     *
     * @return HasMany
     */
    public function floors(): HasMany
    {
        return $this->hasMany(Floor::class);
    }
}
