<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Building;

use App\Models\Complex;
use Illuminate\Contracts\Container\BindingResolutionException;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Rows;

/**
 * Layout формы редактирования здания.
 */
final class BuildingEditLayout extends Rows
{
    /**
     * Возвращает список полей формы.
     *
     * @return Field[]
     * @throws BindingResolutionException
     */
    protected function fields(): array
    {
        return [
            Relation::make('building.complex_id')
                ->title('Жилой комплекс')
                ->fromModel(Complex::class, 'name')
                ->searchColumns('name')
                ->required(),

            Input::make('building.name')
                ->title('Название здания')
                ->placeholder('Например, Корпус 1')
                ->required()
                ->max(255),

            Input::make('building.floors_count')
                ->title('Количество этажей')
                ->type('number')
                ->min(1)
                ->required(),

            Input::make('building.build_year')
                ->title('Год постройки')
                ->type('number')
        ];
    }
}
