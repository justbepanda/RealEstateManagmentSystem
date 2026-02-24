<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Complex;

use App\Enums\ComplexStatusEnum;
use App\Models\Complex;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Str;

/**
 * Описание таблицы списка жилых комплексов.
 */
final class ComplexListLayout extends Table
{
    /**
     * @var string
     */
    protected $target = 'complexes';

    /**
     * Определение колонок таблицы.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('image', 'Фото')
                ->width('100px')
                ->render(function ($model) {
                    $image = $model->attachments->first();

                    return $image
                        ? "<img src='{$image->url()}'
                    class='mw-100 rounded shadow-sm'
                    style='height: 50px; width: 80px; object-fit: cover;'>"
                        : '<span class="text-muted">Нет фото</span>';
                }),

            TD::make('name', 'Название')
                ->sort()
                ->filter(Select::make()->options(
                    Complex::query()
                        ->distinct()
                        ->orderBy('name')
                        ->pluck('name', 'name')
                        ->toArray()
                ))
                ->render(fn(Complex $complex): Link => Link::make($complex->name)
                    ->route('platform.complex.edit', $complex)
                ),

            TD::make('address', 'Адрес')
                ->filter(TD::FILTER_TEXT),

            TD::make('description', 'Описание')
                ->filter(TD::FILTER_TEXT)
                ->render(fn(Complex $complex) => Str::limit($complex->description, 50, '...')),

            TD::make('status', 'Статус')
                ->filter(
                    TD::FILTER_SELECT,
                    collect(ComplexStatusEnum::cases())
                        ->mapWithKeys(fn($status) => [$status->value => $status->label()])
                        ->toArray()
                )
                ->render(fn (Complex $complex) => ComplexStatusEnum::tryFrom($complex->status)?->label() ?? $complex->status),

            TD::make('created_at', 'Создан')
                ->sort()
                ->render(fn(Complex $complex): string => $complex->created_at->format('d.m.Y')),

            TD::make('action', 'Действие')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn (Complex $complex) => Link::make('')
                    ->icon('pencil')
                    ->route('platform.complex.edit', $complex)
                ),
        ];
    }
}
