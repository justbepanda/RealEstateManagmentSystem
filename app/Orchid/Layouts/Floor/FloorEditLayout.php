<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Floor;

use App\Models\Building;
use App\Models\Complex;
use App\Models\Section;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Listener;
use Orchid\Screen\Repository;
use Orchid\Support\Facades\Layout;

/**
 * Layout формы создания и редактирования этажа.
 */
final class FloorEditLayout extends Listener
{
    /**
     * Поля, за изменениями которых Orchid будет следить.
     */
    protected $targets = [
        'complex_id',
        'floor.building_id',
    ];

    /**
     * Метод handle обновляет состояние формы.
     */
    public function handle(Repository $repository, Request $request): Repository
    {
        $complexId = $request->input('complex_id')
            ?? $repository->getContent('complex_id');

        $buildingId = $request->input('floor.building_id')
            ?? $repository->getContent('floor.building_id');

        $sectionId = $request->input('floor.section_id')
            ?? $repository->getContent('floor.section_id');

        return $repository
            ->set('complex_id', $complexId)
            ->set('floor.building_id', $buildingId)
            ->set('floor.section_id', $sectionId);
    }

    /**
     * Отрисовка полей формы.
     */
    protected function layouts(): iterable
    {

        $complexId = $this->query->getContent('complex_id');
        $buildingId = $this->query->getContent('floor.building_id');

        return [
            Layout::rows([
                Select::make('complex_id')
                    ->title('Жилой комплекс')
                    ->fromModel(Complex::class, 'name')
                    ->empty('Выберите комплекс')
                    ->required(),

                Select::make('floor.building_id')
                    ->title('Здание')
                    ->fromQuery(
                        Building::where('complex_id', $complexId),
                        'name'
                    )
                    ->empty('Выберите здание')
                    ->required()
                    ->canSee($complexId !== null),

                Select::make('floor.section_id')
                    ->title('Секция')
                    ->fromQuery(
                        Section::where('building_id', $buildingId),
                        'name'
                    )
                    ->empty('Без секции')
                    ->canSee($buildingId !== null),

                Input::make('floor.number')
                    ->title('Номер этажа')
                    ->type('number')
                    ->required(),

                Input::make('floor.premises_count')
                    ->title('Количество помещений')
                    ->help('Автоматически рассчитывается на основе количества помещений этажа')
                    ->style('opacity: 1; color:#333;')
                    ->readonly()
            ]),
        ];
    }
}
