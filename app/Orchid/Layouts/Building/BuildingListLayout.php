<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Building;

use App\Models\Building;
use App\Models\Complex;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

final class BuildingListLayout extends Table
{
    protected $target = 'buildings';

    /**
     * Столбцы таблицы
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('complex_id', 'Комплекс')
                ->filter(
                    Select::make('complex_id')
                        ->options(
                            Complex::query()
                                ->whereHas('buildings')
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->toArray()
                        )
                        ->empty('Все')
                )
                ->filterValue(fn ($id) =>
                    Complex::whereKey($id)->value('name') ?? $id
                )
                ->render(fn ($building) => $building->complex?->name),

            TD::make('name', 'Здание')
                ->sort()
                ->filter(TD::FILTER_TEXT)
                ->render(fn(Building $building): Link => Link::make($building->name)
                    ->route('platform.building.edit', $building)
                ),


            TD::make('floors_count', 'Этажность')
                ->sort()
                ->render(fn(Building $building) => $building->floors_count),

            TD::make('build_year', 'Год постройки')
                ->sort()
                ->render(fn(Building $building) => $building->build_year),

            TD::make('action', 'Действие')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn (Building $building) => Link::make('')
                    ->icon('pencil')
                    ->route('platform.building.edit', $building)
                ),
        ];
    }
}
