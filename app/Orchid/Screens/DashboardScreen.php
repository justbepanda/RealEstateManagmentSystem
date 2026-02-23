<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\Premise;
use App\Models\PremiseHistory;
use App\Enums\PremiseStatusEnum;
use App\Orchid\Layouts\Charts\SalesChartLayout;
use App\Orchid\Layouts\Charts\StatusPieChartLayout;
use Illuminate\Support\Facades\Cache;
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

            $history = PremiseHistory::with(['premise', 'user'])->latest()->limit(10)->get();

            foreach (PremiseStatusEnum::cases() as $status) {
                $count = (int) $statusStats->get($status->value, 0);
                $metrics[$status->value] = $count;

                $statusPieLabels[] = $status->label();
                $statusPieValues[] = $count;
            }

            return [
                'metrics' => [
                    'available'    => $statusStats->get(PremiseStatusEnum::AVAILABLE->value, 0),
                    'reserved'     => $statusStats->get(PremiseStatusEnum::RESERVED->value, 0),
                    'sold'         => $statusStats->get(PremiseStatusEnum::SOLD->value, 0),
                    'not_for_sale' => $statusStats->get(PremiseStatusEnum::NOT_FOR_SALE->value, 0),
                ],

                'statusPie' => [
                    [
                        'labels' => $statusPieLabels,
                        'values' => $statusPieValues,
                    ]
                ],

                'salesChart' => [
                    [
                        'name'   => 'Продажи',
                        'values' => $salesHistory->values()->toArray(),
                        'labels' => $salesHistory->keys()->toArray(),
                    ]
                ],

                'history' => $history,
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
            Layout::metrics([
                'Свободно'      => 'metrics.available',
                'Забронировано' => 'metrics.reserved',
                'Продано'       => 'metrics.sold',
                'Не для продажи' => 'metrics.not_for_sale',
            ]),

            Layout::columns([
                SalesChartLayout::class,
                StatusPieChartLayout::class,
            ]),

            Layout::table('history', [
                TD::make('created_at', 'Дата')->render(fn($h) => $h->created_at->format('d.m.Y H:i')),
                TD::make('premise_id', 'Объект')->render(fn($h) => "№" . ($h->premise->number ?? 'н/д')),
                TD::make('change', 'Изменение')->render(fn($h) => "{$h->old_value} → {$h->new_value}"),
            ])->title('Последние изменения статусов'),
        ];
    }
}
