<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Attachment\Attachable;
use Orchid\Screen\AsSource;

/**
 * Этажи
 */
final class Floor extends Model
{
    use AsSource;
    use Attachable;
    use HasUlids;
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'building_id',
        'section_id',
        'number',
        'premises_count',
    ];

    /**
     * Этаж принадлежит зданию.
     *
     * @return BelongsTo
     */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * Этаж может принадлежать секции здания.
     *
     * @return BelongsTo
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }


    /**
     * На этаже находятся помещения.
     *
     * @return HasMany
     */
    public function premises(): HasMany
    {
        return $this->hasMany(Premise::class);
    }
}
