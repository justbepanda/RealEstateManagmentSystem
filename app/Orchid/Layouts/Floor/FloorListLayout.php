<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Floor;

use App\Models\Complex;
use App\Models\Floor;
use App\Models\Building;
use App\Models\Section;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\TD;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Fields\Select;

/**
 * Layout для отображения списка этажей.
 */
final class FloorListLayout extends Table
{
    /**
     * Источник данных.
     *
     * @var string
     */
    protected $target = 'floors';

    /**
     * Колонки таблицы.
     *
     * @return array<int, TD>
     */
    protected function columns(): array
    {
        return [
            TD::make('complex_id', 'Комплекс')
                ->filter(
                    Select::make('complex_id')
                        ->fromQuery(Complex::whereHas('buildings.floors'), 'name')
                        ->empty('Все')
                )
                ->filterValue(fn($id) => Complex::where('id', $id)->value('name'))
                ->render(fn (Floor $floor) => $floor->building?->complex?->name ?? ''),

            TD::make('building_name', 'Здание')
                ->filter(
                    Select::make('building_name')
                    ->fromQuery(
                        Building::whereHas('floors')
                            ->select('name')
                            ->distinct()
                            ->orderBy('name'),
                        'name',
                        'name'
                    )
                        ->empty('Все')
                )
                ->filterValue(fn($name) => $name)
                ->render(fn(Floor $floor) => $floor->building?->name ?? ''),

            // Секция
            TD::make('section_name', 'Секция')
                ->filter(
                    Select::make('section_name')
                    ->fromQuery(
                        Section::whereHas('floors')
                            ->select('name')
                            ->distinct()
                            ->orderBy('name'),
                        'name',
                        'name'
                    )
                        ->empty('Все')
                )
                ->filterValue(fn($name) => $name)
                ->render(fn(Floor $floor) => $floor->section?->name ?? ''),


            TD::make('number', 'Номер этажа')
                ->sort()
                ->filter(TD::FILTER_TEXT)
                ->render(fn(Floor $floor): Link => Link::make((string)$floor->number)
                    ->route('platform.floor.edit', $floor)
                ),





            TD::make('premises_count', 'Количество помещений')
                ->sort(),

            TD::make('action', 'Действие')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn(Floor $floor): Link => Link::make('')
                    ->icon('pencil')
                    ->route('platform.floor.edit', $floor)
                ),
        ];
    }
}
