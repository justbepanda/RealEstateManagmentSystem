<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Building;

use App\Models\Building;
use Illuminate\Support\Facades\Cache;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use App\Orchid\Layouts\Building\BuildingListLayout;

final class BuildingListScreen extends Screen
{
    public string $name = 'Здания';
    public string $description = 'Список зданий с фильтром по комплексам';

    /**
     * Данные для таблицы
     */
    public function query(): array
    {
        $params = request()->all();
        ksort($params);

        $cacheKey = 'buildings_list_' . md5(serialize($params));

        return [
            'buildings' => Cache::tags(['buildings', 'references'])->remember($cacheKey, now()->addDay(), function () {
                return Building::with('complex')
                    ->filters()
                    ->defaultSort('name')
                    ->paginate(15);
            })
        ];
    }

    /**
     * Кнопки сверху
     */
    public function commandBar(): array
    {
        return [
            Link::make('Создать')
                ->icon('plus')
                ->route('platform.building.edit'),
        ];
    }

    /**
     * Layouts для отображения
     */
    public function layout(): array
    {
        return [
            BuildingListLayout::class,
        ];
    }
}
