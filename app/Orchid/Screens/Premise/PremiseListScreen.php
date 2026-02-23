<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Premise;

use App\Models\Premise;
use App\Orchid\Layouts\Premise\PremiseListLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layout;
use Throwable;

/**
 * Экран списка помещений.
 */
final class PremiseListScreen extends Screen
{
    /**
     * Заголовок экрана.
     */
    public function name(): ?string
    {
        return 'Помещения';
    }


    /**
     * Кнопки командной панели.
     *
     * @return array<int, Link>
     */
    public function commandBar(): array
    {
        return [
            Link::make('Создать помещение')
                ->icon('plus')
                ->route('platform.premise.edit'),
        ];
    }

    /**
     * Query для списка помещений.
     *
     * @return array<string, mixed>
     */
    public function query(): array
    {
        $premises = Premise::query()
            ->when(request()->input('filter.floor_number'), function ($q, $floorNumber) {
                $q->whereHas('floor', function ($q) use ($floorNumber) {
                    $q->where('number', $floorNumber);
                });
            })
            ->filters()
            ->defaultSort('created_at', 'desc')
            ->paginate();

        return [
            'premises' => $premises,
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
            PremiseListLayout::class,
        ];
    }

    /**
     * Удаление помещения.
     *
     * @throws Throwable
     */
    public function remove(Premise $premise): RedirectResponse
    {
        DB::transaction(function () use ($premise): void {
            $premise->delete();
        });

        Alert::info('Помещение успешно удалено.');

        return redirect()->route('platform.premise.list');
    }
}
