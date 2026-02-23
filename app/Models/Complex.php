<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Screen\AsSource;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

/**
 * Жилые комплексы
 */
final class Complex extends Model
{
    use AsSource;
    use Attachable;
    use Filterable;
    use HasUlids;
    use HasFactory;
    use HasRelationships;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'address',
        'status',
        'latitude',
        'longitude',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => 'string',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    /**
     * Orchid.
     * Поля, по которым разрешена фильтрация и сортировка.
     */
    protected array $allowedFilters = [
        'name' => Like::class,
        'address' => Like::class,
        'status' => Where::class,
    ];

    /**
     * Orchid.
     * Поля, по которым разрешена сортировка.
     */
    protected array $allowedSorts = [
        'name',
        'created_at',
    ];

    /**
     * Комплекс может иметь несколько зданий.
     *
     * @return HasMany
     */
    public function buildings(): HasMany
    {
        return $this->hasMany(Building::class);
    }

    /**
     * Комплекс -> Здания -> Секции -> Этажи -> Помещения
     */
    public function premises(): HasManyDeep
    {
        return $this->hasManyDeep(
            Premise::class,
            [Building::class, Section::class, Floor::class],
            ['complex_id', 'building_id', 'section_id', 'floor_id'],
            ['id', 'id', 'id', 'id']
        );
    }
}
