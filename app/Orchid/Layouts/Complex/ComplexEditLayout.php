<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Complex;

use App\Enums\ComplexStatusEnum;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Map;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Rows;

/**
 * Layout формы редактирования жилого комплекса.
 */
final class ComplexEditLayout extends Rows
{
    /**
     * Возвращает список полей формы.
     *
     * @return Field[]
     */
    protected function fields(): array
    {
        return [
            Input::make('complex.name')
                ->title('Название комплекса')
                ->placeholder('Например, Сиреневые сады')
                ->required()
                ->max(255),

            Select::make('complex.status')
                ->title('Статус')
                ->options(
                    collect(ComplexStatusEnum::cases())
                        ->mapWithKeys(fn($status) => [$status->value => $status->label()])
                        ->toArray()
                )
                ->empty('Выберите статус')
                ->required(),

            Input::make('complex.address')
                ->title('Адрес')
                ->required()
                ->max(255),

            Quill::make('complex.description')
                ->title('Описание')
                ->placeholder('Подробное описание комплекса...'),

            Map::make('complex_map')
                ->title('Координаты на карте')
                ->help('Перетащите маркер, чтобы выбрать местоположение')
                ->value([
                    'lat' => $this->query['complex']->latitude ?? 55.7558,
                    'lng' => $this->query['complex']->longitude ?? 37.6173,
                ])
                ->zoom(12)
                ->height('400px')
                ->required(),

            Upload::make('complex.attachments')
                ->title('Галерея')
                ->groups('photo')
                ->storage('public')
                ->maxFiles(5)
                ->acceptedFiles('image/*'),
        ];
    }
}
