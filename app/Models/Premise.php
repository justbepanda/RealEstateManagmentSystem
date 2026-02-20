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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => PremiseTypeEnum::class,
            'status' => PremiseStatusEnum::class,
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
     * Помещение находится на этаже.
     *
     * @return BelongsTo
     */
    public function floor(): BelongsTo
    {
        return $this->belongsTo(Floor::class);
    }
}
