<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Floor;

use App\Models\Floor;
use App\Orchid\Layouts\Floor\FloorListLayout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Экран списка этажей.
 */
final class FloorListScreen extends Screen
{
    /**
     * Заголовок экрана.
     */
    public function name(): ?string
    {
        return 'Этажи';
    }

    /**
     * Кнопки командной панели.
     *
     * @return array<int, Link>
     */
    public function commandBar(): array
    {
        return [
            Link::make('Создать этаж')
                ->icon('plus')
                ->route('platform.floor.edit'),
        ];
    }

    /**
     * Query для списка этажей.
     *
     * @return array<string, mixed>
     */
    public function query(): array
    {
        return [
            'floors' => Floor::with(['building.complex', 'section'])
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
                    $q->whereHas('section', function ($q) use ($name) {
                        $q->whereIn('name', (array)$name);
                    });
                })
                ->filters()
                ->defaultSort('created_at', 'desc')
                ->paginate()
        ];
    }

    /**
     * Layout экрана.
     *
     * @return array<int, class-string<Layout>>
     */
    public function layout(): array
    {
        return [
            FloorListLayout::class,
        ];
    }

    /**
     * Удаление этажа.
     *
     * @param Floor $floor
     * @return RedirectResponse
     * @throws Throwable
     */
    public function remove(Floor $floor): RedirectResponse
    {
        DB::transaction(function () use ($floor): void {
            $floor->delete();
        });

        Alert::info('Этаж успешно удалён.');

        return redirect()->route('platform.floor.list');
    }
}
