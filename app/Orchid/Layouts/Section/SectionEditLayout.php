<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Section;

use App\Models\Building;
use App\Models\Complex;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Listener;
use Orchid\Screen\Repository;
use Orchid\Support\Facades\Layout;

/**
 * Layout формы редактирования секции.
 */
final class SectionEditLayout extends Listener
{
    /**
     * Поля для отслеживания
     */
    protected $targets = [
        'complex_id',
    ];

    /**
     * Обновляет состояние формы при изменении ЖК.
     */
    public function handle(Repository $repository, Request $request): Repository
    {
        return $repository->set('complex_id', $request->input('complex_id'));
    }

    /**
     * Отрисовка полей формы.
     */
    protected function layouts(): iterable
    {
        $complexId = $this->query->getContent('complex_id');

        return [
            Layout::rows([
                Select::make('complex_id')
                    ->title('Жилой комплекс')
                    ->fromModel(Complex::class, 'name')
                    ->empty('Выберите комплекс')
                    ->help('Выберите ЖК, чтобы отфильтровать список зданий'),

                Select::make('section.building_id')
                ->title('Здание')
                    ->fromQuery(
                        Building::where('complex_id', $complexId),
                        'name'
                    )
                    ->empty('Выберите здание')
                    ->required()
                    ->canSee($complexId !== null),

                Input::make('section.name')
                    ->title('Название секции')
                    ->placeholder('Например, Секция 1 или Подъезд А')
                    ->required(),

                Input::make('section.floors_count')
                    ->title('Количество этажей в секции')
                    ->type('number')
                    ->required(),
            ]),
        ];
    }
}
