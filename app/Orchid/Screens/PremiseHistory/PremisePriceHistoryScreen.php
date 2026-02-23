<?php

declare(strict_types=1);

namespace App\Orchid\Screens\PremiseHistory;

use App\Models\PremiseHistory;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class PremisePriceHistoryScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'history' => PremiseHistory::with(['premise', 'user'])
                ->where('type', 'price')
                ->latest()
                ->paginate(20),
        ];
    }

    public function name(): ?string
    {
        return 'История цен';
    }

    public function description(): ?string
    {
        return 'Журнал изменений стоимости объектов недвижимости';
    }

    public function layout(): iterable
    {
        return [
            Layout::table('history', [
                TD::make('created_at', 'Дата')
                    ->render(fn(PremiseHistory $history) => $history->created_at->format('d.m.Y H:i')),

                TD::make('premise_id', 'Объект')
                    ->render(fn(PremiseHistory $history) => $history->premise
                        ? "№" . $history->premise->number
                        : 'Удалено'),

                TD::make('old_value', 'Было')
                    ->render(fn($h) => number_format((float)$h->old_value, 0, '.', ' ') . ' ₽'),

                TD::make('new_value', 'Стало')
                    ->render(fn($h) => number_format((float)$h->new_value, 0, '.', ' ') . ' ₽'),

                TD::make('diff', 'Изменение')
                    ->render(function ($h) {
                        $diff = (float)$h->new_value - (float)$h->old_value;
                        $color = $diff > 0 ? 'text-success' : 'text-danger';
                        $prefix = $diff > 0 ? '+' : '';

                        return "<span class='$color'>$prefix" . number_format($diff, 0, '.', ' ') . " ₽</span>";
                    }),

                TD::make('user_id', 'Сотрудник')
                    ->render(fn(PremiseHistory $history) => $history->user->name ?? 'Система'),
            ]),
        ];
    }
}
