<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Building;

use App\Models\Building;
use App\Orchid\Layouts\Building\BuildingEditLayout;
use DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Throwable;

/**
 * Экран создания и редактирования здания.
 */
final class BuildingEditScreen extends Screen
{
    /**
     * @var Building|null
     */
    public ?Building $building = null;

    /**
     * Загружает данные экрана.
     */
    public function query(?Building $building = null): array
    {
        if ($building?->exists) {
            $this->building = $building;
        } else {
            $this->building = new Building();
        }

        return [
            'building' => $this->building,
        ];
    }

    /**
     * Заголовок экрана.
     */
    public function name(): ?string
    {
        return $this->building->exists
            ? 'Редактировать здание'
            : 'Создать новое здание';
    }

    /**
     * Кнопки действий.
     *
     * @return Action[]
     */
    public function commandBar(): array
    {
        return [
            Button::make('Сохранить')
                ->icon('check')
                ->method('save'),

            Button::make('Удалить')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->building->exists),
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
            BuildingEditLayout::class,
        ];
    }

    /**
     * Сохранение здания.
     *
     * @throws Throwable
     */
    public function save(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'building.complex_id'   => 'required|exists:complexes,id',
            'building.name'         => 'required|string|max:255',
            'building.floors_count' => 'required|integer|min:1',
            'building.build_year'   => 'nullable|integer',
        ]);

        $building = $this->building ?? new Building();

        DB::transaction(function () use ($validated, $building) {
            $building->fill($validated['building']);
            $building->save();

            Cache::tags(['buildings', 'references', 'statistics'])->flush();
        });

        Alert::info('Здание успешно сохранено.');

        return redirect()->route('platform.building.edit', $building->id);
    }

    /**
     * Удаление здания.
     *
     * @throws Throwable
     */
    public function remove(): RedirectResponse
    {
        DB::transaction(function () {
            $this->building->delete();

            Cache::tags(['buildings', 'references', 'statistics'])->flush();
        });

        Alert::info('Здание успешно удалено.');

        return redirect()->route('platform.building.list');
    }
}
