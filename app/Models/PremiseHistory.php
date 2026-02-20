<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * История изменений помещений
 */
final class PremiseHistory extends Model
{
    use HasUlids;

    public $timestamps = false;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'premise_id',
        'user_id',
        'type',
        'old_value',
        'new_value',
    ];

    /**
     * Связь с помещением.
     *
     * @return BelongsTo
     */
    public function premise(): BelongsTo
    {
        return $this->belongsTo(Premise::class);
    }

    /**
     * Связь с пользователем, который совершил изменение.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
