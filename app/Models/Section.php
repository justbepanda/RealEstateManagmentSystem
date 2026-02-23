<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Screen\AsSource;

/**
 * Секция здания
 */
final class Section extends Model
{
    use AsSource;
    use HasUlids;
    use HasFactory;
    use Filterable;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'building_id',
        'name',
        'floors_count',
    ];

    /**
     * Orchid.
     * Поля, по которым разрешена фильтрация и сортировка.
     */
    protected array $allowedFilters = [
        'name' => Like::class,
        'building_id' => Where::class,
    ];

    /**
     * Orchid.
     * Поля, по которым разрешена сортировка.
     */
    protected array $allowedSorts = [
        'name',
        'building_id',
        'floors_count',
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
