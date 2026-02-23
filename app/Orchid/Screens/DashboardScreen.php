<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\Premise;
use App\Models\PremiseHistory;
use App\Enums\PremiseStatusEnum;
use App\Enums\PremiseTypeEnum;
use App\Orchid\Layouts\Charts\SalesChartLayout;
use App\Orchid\Layouts\Charts\StatusPieChartLayout;
use Illuminate\Support\Facades\Cache;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;
use Illuminate\Support\Facades\DB;

class DashboardScreen extends Screen
{
    /**
     * @return iterable
     */
    public function query(): iterable
    {
        return Cache::tags(['statistics'])->remember('admin_dashboard_data', now()->addMinutes(30), function () {

            // Статистика по статусам
            $statusStats = Premise::groupBy('status')
                ->select('status', DB::raw('count(*) as count'))
                ->pluck('count', 'status');

            // История продаж
            $salesHistory = PremiseHistory::where('type', 'status')
                ->where('new_value', PremiseStatusEnum::SOLD->value)
                ->where('created_at', '>=', now()->subMonths(12))
                ->get()
                ->unique('premise_id')
                ->groupBy(fn($item) => $item->created_at->format('M Y'))
                ->map(fn($group) => $group->count());

            // Последние изменения статусов
            $history = PremiseHistory::with(['premise', 'user'])
                ->where('type', 'status')
                ->latest()
                ->limit(10)
                ->get();

            // Метрики и данные для круговой диаграммы
            $metrics = [];
            $statusPieLabels = [];
            $statusPieValues = [];

            foreach (PremiseStatusEnum::cases() as $status) {
                $count = (int) $statusStats->get($status->value, 0);
                $metrics[$status->value] = $count;

                $statusPieLabels[] = $status->label();
                $statusPieValues[] = $count;
            }

            // Топ-10 самых дорогих помещений
            $topPremises = Premise::with([
                'floor.section.building.complex',
                'floor.building.complex',
            ])
                ->orderByDesc('price_base')
                ->limit(10)
                ->get();

            return [
                'metrics'      => $metrics,
                'statusPie'    => [
                    [
                        'labels' => $statusPieLabels,
                        'values' => $statusPieValues,
                    ]
                ],
                'salesChart'   => [
                    [
                        'name'   => 'Продажи',
                        'values' => $salesHistory->values()->toArray(),
                        'labels' => $salesHistory->keys()->toArray(),
                    ]
                ],
                'history'      => $history,
                'topPremises'  => $topPremises,
            ];
        });
    }

    /**
     * The name of the screen to be displayed in the header.
     */
    public function name(): ?string
    {
        return 'Панель управления';
    }

    /**
     * A description of the screen to be displayed in the header.
     */
    public function description(): ?string
    {
        return 'Общая статистика по жилым комплексам и продажам';
    }

    public function layout(): iterable
    {
        return [
            Layout::metrics(
                collect(PremiseStatusEnum::cases())
                    ->mapWithKeys(fn($status) => [$status->label() => "metrics.{$status->value}"])
                    ->toArray()
            ),

            Layout::columns([
                SalesChartLayout::class,
                StatusPieChartLayout::class,
            ]),

            Layout::table('history', [
                TD::make('created_at', 'Дата')
                    ->render(fn(PremiseHistory $h) => $h->created_at->format('d.m.Y H:i')),

                TD::make('premise_id', '№ помещения')
                    ->render(fn(PremiseHistory $h) =>
                    $h->premise
                        ? Link::make("№" . $h->premise->number)
                        ->route('platform.premise.edit', $h->premise->id)
                        : 'н/д'
                    ),

                TD::make('change', 'Изменение')
                    ->render(fn(PremiseHistory $h) =>
                        $this->getStatusLabel($h->old_value) . ' → ' . $this->getStatusLabel($h->new_value)
                    ),
            ])->title('Последние изменения статусов'),

            Layout::table('topPremises', [
                TD::make('number', '№ помещения')
                    ->render(fn (Premise $p) =>
                    Link::make("№{$p->number}")
                        ->route('platform.premise.edit', $p)
                    ),

                TD::make('complex', 'ЖК')
                    ->render(function (Premise $p) {
                        $complex = $p->floor?->section?->building?->complex
                            ?? $p->floor?->building?->complex;

                        return $complex?->name ?? '—';
                    }),

                TD::make('type', 'Тип')
                    ->render(fn(Premise $p) => PremiseTypeEnum::tryFrom($p->type)?->label() ?? $p->type),

                TD::make('status', 'Статус')
                    ->render(fn(Premise $p) => PremiseStatusEnum::tryFrom($p->status)?->label() ?? $p->status),

                TD::make('price_base', 'Цена, ₽')
                    ->align(TD::ALIGN_RIGHT)
                    ->render(fn(Premise $p) => number_format($p->price_base, 0, '.', ' ')),
            ])->title('Топ-10 самых дорогих помещений'),
        ];
    }

    /**
     * Превращаем техническое значение статуса в читаемый label через Enum
     */
    private function getStatusLabel(?string $value): string
    {
        return PremiseStatusEnum::tryFrom($value)?->label() ?? ($value ?? '-');
    }
}
