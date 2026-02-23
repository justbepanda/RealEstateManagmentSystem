<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PremiseStatusEnum;
use App\Enums\PremiseTypeEnum;
use App\Observers\PremiseObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereMaxMin;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;

/**
 * Помещения
 */
#[ObservedBy(PremiseObserver::class)]
final class Premise extends Model
{
    use AsSource;
    use Attachable;
    use Filterable;
    use HasUlids;
    use HasFactory;
    use Chartable;


    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'floor_id',
        'number',
        'type',
        'status',
        'rooms',
        'area_total',
        'area_living',
        'area_kitchen',
        'price_base',
        'price_discount',
        'price_per_m2',
        'features',
    ];

    /**
     * Orchid.
     * Поля, по которым разрешена фильтрация и сортировка.
     */
    protected array $allowedFilters = [
        'number' => Where::class,
        'type' => Where::class,
        'status' => Where::class,
        'rooms' => Where::class,
        'price_base' => WhereMaxMin::class,
        'price_discount' => WhereMaxMin::class,
        'area_total' => WhereMaxMin::class,
        'area_living' => WhereMaxMin::class,
        'area_kitchen' => WhereMaxMin::class,
    ];

    /**
     * Orchid.
     * Поля, по которым разрешена сортировка.
     */
    protected array $allowedSorts = [
        'floor_id',
        'number',
        'type',
        'status',
        'rooms',
        'area_total',
        'area_living',
        'area_kitchen',
        'price_base',
        'price_discount',
        'price_per_m2',
        'features',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => 'string',
            'status' => 'string',
            'features' => 'array',
            'area_total' => 'float',
            'area_living' => 'float',
            'area_kitchen' => 'float',
            'price_base' => 'integer',
            'price_discount' => 'integer',
            'price_per_m2' => 'integer',
        ];
    }

    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    protected static function booted(): void
    {
        Premise::saving(function ($premise) {
            if ($premise->area_total > 0) {

                $actualPrice = ($premise->price_discount > 0)
                    ? $premise->price_discount
                    : $premise->price_base;

                $premise->price_per_m2 = round($actualPrice / $premise->area_total);
            }
        });
    }

    /**
     * Помещение находится на этаже.
     *
     * @return BelongsTo
     */
    public function floor(): BelongsTo
    {
        return $this->belongsTo(Floor::class);
    }
}
