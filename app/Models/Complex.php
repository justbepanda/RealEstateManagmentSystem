<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ComplexStatusEnum;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

/**
 * Жилые комплексы
 */
final class Complex extends Model
{
    use AsSource;
    use Attachable;
    use Filterable;
    use HasUlids;

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
            'status' => ComplexStatusEnum::class,
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }


    /**
     * Комплекс может иметь несколько зданий.
     *
     * @return HasMany
     */
    public function buildings(): HasMany
    {
        return $this->hasMany(Building::class);
    }
}
