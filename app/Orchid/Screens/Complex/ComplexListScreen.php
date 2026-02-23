<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Complex;

use App\Models\Complex;
use App\Orchid\Layouts\Complex\ComplexListLayout;
use Cache;
use Orchid\Screen\Action;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;

/**
 * Экран списка всех жилых комплексов.
 */
final class ComplexListScreen extends Screen
{
    public string $name = 'Жилые комплексы';
    public string $description = 'Управление списком объектов недвижимости';

    /**
     * Загрузка данных для экрана.
     *
     * @return array<string, mixed>
     */
    public function query(): array
    {
        $params = request()->all();
        ksort($params);

        $cacheKey = 'complexes_list_' . md5(serialize($params));

        return [
            'complexes' => Cache::tags(['complexes', 'references'])->remember($cacheKey, now()->addDay(), function () {
                return Complex::filters()
                    ->defaultSort('created_at', 'desc')
                    ->paginate();
            }),
        ];
    }

    /**
     * Кнопки управления
     *
     * @return Action[]
     */
    public function commandBar(): array
    {
        return [
            Link::make('Добавить ЖК')
                ->icon('plus')
                ->route('platform.complex.edit'),
        ];
    }

    /**
     * Макеты экрана.
     *
     * @return Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            ComplexListLayout::class,
        ];
    }
}
