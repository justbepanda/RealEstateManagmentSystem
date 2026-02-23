<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Section;

use App\Models\Section;
use App\Orchid\Layouts\Section\SectionListLayout;
use Illuminate\Support\Facades\Cache;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layout;
use Illuminate\Http\RedirectResponse;
use DB;
use Throwable;

/**
 * Экран списка секций.
 */
final class SectionListScreen extends Screen
{
    /**
     * Заголовок экрана.
     */
    public function name(): ?string
    {
        return 'Секции зданий';
    }

    /**
     * Кнопки командной панели.
     */
    public function commandBar(): array
    {
        return [
            Link::make('Создать секцию')
                ->icon('plus')
                ->route('platform.section.edit'),
        ];
    }

    /**
     * Query для списка секций.
     *
     * @return array
     */
    public function query(): array
    {
        $params = request()->all();
        ksort($params);

        $cacheKey = 'sections_list_' . md5(serialize($params));

        return [
            'sections' => Cache::tags(['sections', 'references'])->remember($cacheKey, now()->addDay(), function () {
                return Section::with('building.complex')
                    ->when(request()->input('filter.complex_id'), function ($q, $complexId) {
                        $q->whereHas('building', function ($q) use ($complexId) {
                            $q->whereIn('complex_id', (array)$complexId);
                        });
                    })
                    ->when(request()->input('filter.building_name'), function ($q, $name) {
                        $q->whereHas('building', function ($q) use ($name) {
                            $q->whereIn('name', (array)$name);
                        });
                    })
                    ->when(request()->input('filter.section_name'), function ($q, $name) {
                        $q->whereIn('name', (array)$name);
                    })
                    ->filters()
                    ->defaultSort('id', 'desc')
                    ->paginate();
            }),
        ];
    }

    /**
     * Layout экрана.
     *
     * @return Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            SectionListLayout::class,
        ];
    }

    /**
     * Удаление секции.
     *
     * @param Section $section
     * @return RedirectResponse
     * @throws Throwable
     */
    public function remove(Section $section): RedirectResponse
    {
        DB::transaction(function () use ($section) {
            $section->delete();
        });

        Alert::info('Секция успешно удалена.');

        return redirect()->route('platform.section.list');
    }
}
