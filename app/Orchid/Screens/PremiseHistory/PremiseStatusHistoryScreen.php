<?php

declare(strict_types=1);

namespace App\Orchid\Screens\PremiseHistory;

use App\Enums\PremiseStatusEnum;
use App\Models\PremiseHistory;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class PremiseStatusHistoryScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'history' => PremiseHistory::with(['premise', 'user'])
                ->where('type', 'status')
                ->latest()
                ->paginate(20),
        ];
    }

    public function name(): ?string
    {
        return 'История статусов';
    }

    public function description(): ?string
    {
        return 'Журнал изменений состояний объектов недвижимости';
    }

    public function layout(): iterable
    {
        return [
            Layout::table('history', [
                TD::make('created_at', 'Дата и время')
                    ->render(fn(PremiseHistory $history) => $history->created_at->format('d.m.Y H:i')),

                TD::make('premise_id', 'Объект')
                    ->render(fn(PremiseHistory $history) => $history->premise
                        ? "№" . $history->premise->number
                        : 'Удалено'),

                TD::make('old_value', 'Старый статус')
                    ->render(fn($h) => $this->getStatusLabel($h->old_value)),

                TD::make('new_value', 'Новый статус')
                    ->render(fn($h) => $this->getStatusLabel($h->new_value)),

                TD::make('user_id', 'Сотрудник')
                    ->render(fn(PremiseHistory $history) => $history->user->name ?? 'Система'),
            ]),
        ];
    }

    /**
     * Превращаем техническое имя в читаемое через Enum
     */
    private function getStatusLabel(string $value): string
    {
        $status = PremiseStatusEnum::tryFrom($value);

        return $status ? $status->label() : $value;
    }
}
