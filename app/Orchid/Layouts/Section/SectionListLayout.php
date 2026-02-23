<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Section;

use App\Models\Complex;
use App\Models\Section;
use App\Models\Building;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\TD;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Fields\Select;

/**
 * Layout для отображения списка секций.
 */
final class SectionListLayout extends Table
{
    /**
     * @var string
     */
    protected $target = 'sections';

    /**
     * Колонки таблицы.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('complex_id', 'Комплекс')
                ->filter(
                    Select::make('complex_id')
                        ->fromQuery(Complex::whereHas('buildings.sections'), 'name')
                        ->empty('Все ЖК')
                )
                ->filterValue(fn($id) => Complex::where('id', $id)->value('name'))
                ->render(fn (Section $section) => $section->building?->complex?->name ?? '-'),

            TD::make('building_name', 'Здание')
                ->filter(
                    Select::make('building_name')
                        ->fromQuery(
                            Building::whereHas('sections')
                                ->select('name')
                                ->distinct()
                                ->orderBy('name'),
                            'name',
                            'name'
                        )
                        ->empty('Все здания')
                )
                ->filterValue(fn($name) => $name)
                ->render(fn (Section $section) => $section->building?->name ?? '-'),


            TD::make('name', 'Название секции')
                ->sort()
                ->filter(
                    Select::make('section_name')
                    ->fromQuery(
                        Section::query()
                            ->select('name')
                            ->distinct()
                            ->orderBy('name'),
                        'name',
                        'name'
                    )
                        ->empty('Все')
                )
                ->render(fn(Section $section) => Link::make($section->name)
                    ->route('platform.section.edit', $section)
                    ->bold()
                ),

            TD::make('floors_count', 'Этажей')
                ->sort(),

            TD::make('action', 'Действие')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn (Section $section) => Link::make('')
                    ->icon('pencil')
                    ->route('platform.section.edit', $section)
                ),
        ];
    }
}
