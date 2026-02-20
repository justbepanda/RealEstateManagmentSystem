<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Floor;
use App\Models\Premise;
use App\Models\PremiseHistory;
use Illuminate\Support\Facades\Auth;

/**
 * Помещения.
 * Наблюдатель.
 */
final class PremiseObserver
{

    /**
     * @param Premise $premise
     * @return void
     */
    public function created(Premise $premise): void
    {
        // Изменение кол-ва помещений на этаже после добавления помещения
        if ($premise->floor_id) {
            Floor::where('id', $premise->floor_id)
                ->increment('premises_count');
        }
    }


    /**
     * @param Premise $premise
     * @return void
     */
    public function updated(Premise $premise): void
    {
        // Создание истории смены статуса помещения
        if ($premise->isDirty('status')) {
            $this->logHistory($premise, 'status',
                (string)$premise->getOriginal('status'),
                (string)$premise->status->value
            );
        }

        // Создание истории изменения базовой цены помещения
        if ($premise->isDirty('price_base')) {
            $this->logHistory($premise, 'price',
                (string)$premise->getOriginal('price_base'),
                (string)$premise->price_base
            );
        }
    }


    /**
     * @param Premise $premise
     * @return void
     */
    public function deleted(Premise $premise): void
    {
        // Изменение кол-ва помещений на этаже после удаления помещения
        $premise->floor()->decrement('premises_count');
    }


    /**
     * Записываем историю изменений
     *
     * @param Premise $premise
     * @param string $type
     * @param string|null $old
     * @param string $new
     * @return void
     */
    private function logHistory(Premise $premise, string $type, ?string $old, string $new): void
    {
        PremiseHistory::create([
            'premise_id' => $premise->id,
            'user_id'    => Auth::id(),
            'type'       => $type,
            'old_value'  => $old,
            'new_value'  => $new,
        ]);
    }
}
