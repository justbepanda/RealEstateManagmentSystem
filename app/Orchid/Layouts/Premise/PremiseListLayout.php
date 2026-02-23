<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Premise;

use App\Enums\PremiseStatusEnum;
use App\Enums\PremiseTypeEnum;
use App\Models\Premise;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\NumberRange;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\TD;
use Orchid\Screen\Layouts\Table;

/**
 * Layout списка помещений.
 */
final class PremiseListLayout extends Table
{
    /**
     * Источник данных.
     *
     * @var string
     */
    protected $target = 'premises';

    /**
     * Колонки таблицы.
     *
     * @return array<int, TD>
     */
    protected function columns(): array
    {
        return [
            TD::make('number', '№')
                ->sort()
                ->filter(TD::FILTER_TEXT)
                ->render(fn(Premise $premise): Link => Link::make($premise->number)
                    ->route('platform.premise.edit', $premise)
                ),

            TD::make('floor_number', 'Этаж')
                ->filter(
                    Input::make('floor_number')
                        ->type('number')
                        ->placeholder('Введите номер этажа')
                )
                ->filterValue(fn($floorNumber) => $floorNumber)
                ->render(fn(Premise $premise) => $premise->floor?->number ?? '-')
                ->sort(),


            TD::make('type', 'Тип')
                ->filter(
                    TD::FILTER_SELECT,
                    collect(PremiseTypeEnum::cases())
                        ->mapWithKeys(fn($type) => [$type->value => $type->label()])
                        ->toArray()
                )
                ->render(fn (Premise $premise) => PremiseTypeEnum::tryFrom($premise->type)?->label() ?? $premise->type),

            TD::make('status', 'Статус')
                ->filter(TD::FILTER_SELECT,
                    collect(PremiseStatusEnum::cases())
                        ->mapWithKeys(fn($status) => [$status->value => $status->label()])
                        ->toArray()
                )
                ->render(fn (Premise $premise) => PremiseStatusEnum::tryFrom($premise->status)?->label() ?? $premise->status),

            TD::make('rooms', 'Кол-во комнат')
                ->sort()
                ->filter(
                    Select::make('rooms')
                        ->fromQuery(Premise::query()->select('rooms')->distinct()->orderBy('rooms'), 'rooms', 'rooms')
                )
                ->render(fn (Premise $premise) => $premise->rooms),

            TD::make('area_total', 'Общая площадь')
                ->sort()
                ->filter(NumberRange::make()->title('Диапазон площади')),

            TD::make('price_base', 'Цена')
                ->filter(NumberRange::make()->title('Диапазон цен'))
                ->align(TD::ALIGN_RIGHT)
                ->sort()
                ->render(fn($premise) => $premise->price_base !== null
                    ? number_format($premise->price_base, 0, '.', ' ') . ' ₽'
                    : ''),

            TD::make('price_discount', 'Цена со скидкой')
                ->filter(NumberRange::make()->title('Диапазон цен'))
                ->align(TD::ALIGN_RIGHT)
                ->sort()
                ->render(fn($premise) => $premise->price_discount !== null
                    ? number_format($premise->price_discount, 0, '.', ' ') . ' ₽'
                    : ''),


            TD::make('action', 'Действие')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn(Premise $premise): Link => Link::make('')
                    ->icon('pencil')
                    ->route('platform.premise.edit', $premise)
                ),
        ];
    }
}
