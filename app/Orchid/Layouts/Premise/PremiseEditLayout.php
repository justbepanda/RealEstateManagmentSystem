<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Premise;

use App\Models\Building;
use App\Models\Complex;
use App\Models\Section;
use App\Models\Floor;
use App\Enums\PremiseStatusEnum;
use App\Enums\PremiseTypeEnum;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Listener;
use Orchid\Screen\Repository;
use Orchid\Support\Facades\Layout;

/**
 * Layout формы создания и редактирования помещения с полной цепочкой зависимостей.
 */
final class PremiseEditLayout extends Listener
{
    /**
     * Поля, за которыми следим.
     */
    protected $targets = [
        'complex_id',
        'building_id',
        'section_id',
    ];

    /**
     * Обработка изменений в цепочке.
     */
    public function handle(Repository $repository, Request $request): Repository
    {
        return $repository
            ->set('complex_id', $request->input('complex_id'))
            ->set('building_id', $request->input('building_id'))
            ->set('section_id', $request->input('section_id'));
    }

    /**
     * Отрисовка полей.
     */
    protected function layouts(): iterable
    {
        $complexId  = $this->query->getContent('complex_id');
        $buildingId = $this->query->getContent('building_id');
        $sectionId  = $this->query->getContent('section_id');

        return [
            Layout::rows([
                Select::make('complex_id')
                    ->title('Жилой комплекс')
                    ->fromModel(Complex::class, 'name')
                    ->empty('Выберите комплекс'),

                Select::make('building_id')
                    ->title('Здание')
                    ->fromQuery(Building::where('complex_id', $complexId), 'name')
                    ->empty('Выберите здание')
                    ->canSee($complexId !== null),

                Select::make('section_id')
                    ->title('Секция')
                    ->fromQuery(Section::where('building_id', $buildingId), 'name')
                    ->empty('Без секции')
                    ->canSee($buildingId !== null),

                Select::make('premise.floor_id')
                    ->title('Этаж')
                    ->fromQuery(
                        Floor::where(function ($query) use ($sectionId, $buildingId) {
                            if ($sectionId) {
                                $query->where('section_id', $sectionId);
                            } else {
                                $query->where('building_id', $buildingId);
                            }
                        })->orderBy('number'),
                        'number'
                    )
                    ->empty('Выберите этаж')
                    ->required()
                    ->canSee($buildingId !== null),

                Input::make('premise.number')
                    ->title('Номер помещения')
                    ->required()
                    ->max(255),

                Select::make('premise.type')
                    ->title('Тип')
                    ->options(
                        collect(PremiseTypeEnum::cases())
                            ->mapWithKeys(fn($type) => [$type->value => $type->label()])
                            ->toArray()
                    )
                    ->required(),

                Select::make('premise.status')
                    ->title('Статус')
                    ->options(
                        collect(PremiseStatusEnum::cases())
                            ->mapWithKeys(fn($status) => [$status->value => $status->label()])
                            ->toArray()
                    )
                    ->required(),

                Input::make('premise.rooms')
                    ->title('Количество комнат')
                    ->type('number')
                    ->min(0)
                    ->required(),

                Input::make('premise.area_total')
                    ->title('Общая площадь, м²')
                    ->type('number')
                    ->step(0.01)
                    ->required(),

                Input::make('premise.area_living')
                    ->title('Жилая площадь, м²')
                    ->type('number')
                    ->step(0.01)
                    ->required(),

                Input::make('premise.area_kitchen')
                    ->title('Площадь кухни, м²')
                    ->type('number')
                    ->step(0.01)
                    ->required(),

                Input::make('premise.price_base')
                    ->title('Цена')
                    ->mask([
                        'alias' => 'numeric',
                        'groupSeparator' => ' ',
                        'digits' => 0,
                        'autoGroup' => true,
                        'removeMaskOnSubmit' => true,
                    ])
                    ->required(),

                Input::make('premise.price_discount')
                    ->title('Цена со скидкой')
                    ->mask([
                        'alias' => 'numeric',
                        'groupSeparator' => ' ',
                        'digits' => 0,
                        'autoGroup' => true,
                        'removeMaskOnSubmit' => true,
                    ]),

                Input::make('premise.price_per_m2')
                    ->title('Цена за м²')
                    ->readonly()
                    ->style('opacity: 1; color: #333;')
                    ->mask([
                        'alias' => 'numeric',
                        'groupSeparator' => ' ',
                        'autoGroup' => true,
                    ])
                    ->help('Рассчитывается автоматически на основе цены и площади'),

                Upload::make('premise.attachments')
                    ->title('План помещения')
                    ->groups('layout')
                    ->storage('public')
                    ->maxFiles(1)
                    ->acceptedFiles('image/*'),

                Upload::make('premise.attachments')
                    ->title('Галерея')
                    ->groups('gallery')
                    ->storage('public')
                    ->maxFiles(5)
                    ->acceptedFiles('image/*'),

                Select::make('premise.features.')
                ->title('Особенности помещения')
                    ->options([
                        'balcony'   => 'Балкон',
                        'loggia'    => 'Лоджия',
                        'high_ceil' => 'Высокие потолки',
                        'view_park' => 'Вид на парк',
                        'view_yard' => 'Вид во двор',
                    ])
                    ->multiple()
                    ->help('Выберите все подходящие характеристики')
            ]),
        ];
    }
}
