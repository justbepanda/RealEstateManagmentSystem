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
 * Здания
 */
final class Building extends Model
{
    use AsSource;
    use HasUlids;
    use HasFactory;
    use Filterable;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'complex_id',
        'name',
        'floors_count',
        'build_year',
    ];

    protected $casts = [
        'floors_count' => 'integer',
        'build_year' => 'integer',
    ];

    /**
     * Orchid.
     * Поля, по которым разрешена фильтрация и сортировка.
     */
    protected array $allowedFilters = [
        'name' => Like::class,
        'complex_id' => Where::class,
    ];

    /**
     * Orchid.
     * Поля, по которым разрешена сортировка.
     */
    protected array $allowedSorts = [
        'name',
        'floors_count',
        'build_year',
        'created_at',
    ];

    /**
     * Здание принадлежит комплексу.
     *
     * @return BelongsTo
     */
    public function complex(): BelongsTo
    {
        return $this->belongsTo(Complex::class);
    }

    /**
     * Здание может иметь несколько секций.
     *
     * @return HasMany
     */
    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    /**
     * Здание может иметь этажи.
     *
     * @return HasMany
     */
    public function floors(): HasMany
    {
        return $this->hasMany(Floor::class);
    }
}
