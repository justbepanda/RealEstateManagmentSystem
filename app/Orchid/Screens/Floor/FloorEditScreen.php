<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Floor;

use App\Models\Building;
use App\Models\Floor;
use App\Models\Section;
use App\Orchid\Layouts\Floor\FloorEditLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Layout;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Экран создания и редактирования этажа.
 */
final class FloorEditScreen extends Screen
{
    /**
     * Текущий этаж.
     *
     * @var Floor|null
     */
    public ?Floor $floor = null;

    /**
     * Загружает данные экрана.
     *
     * @param Floor|null $floor
     * @return array<string, mixed>
     */
    public function query(?Floor $floor = null): array
    {
        if ($floor?->exists) {
            $this->floor = $floor;
        } else {
            $this->floor = new Floor();
        }

        return [
            'floor'             => $this->floor,
            'complex_id'        => $this->floor->building?->complex_id,
            'floor.building_id' => $this->floor->building_id,
            'floor.section_id'  => $this->floor->section_id,
        ];
    }

    /**
     * Заголовок экрана.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->floor->exists
            ? 'Редактировать этаж'
            : 'Создать новый этаж';
    }

    /**
     * Кнопки командной панели.
     *
     * @return array<int, Button>
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
                ->canSee($this->floor->exists),

            Button::make('Тест')
                ->method('test')
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
            FloorEditLayout::class,
        ];
    }

    /**
     * Сохранение этажа.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function save(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'floor.building_id' => 'required|exists:buildings,id',
            'floor.section_id'  => 'nullable|exists:sections,id',
            'floor.number'      => 'required|integer|min:1',
        ]);

        $floor = $this->floor ?? new Floor();

        $floor->fill($validated['floor']);
        $floor->save();

        Alert::info('Этаж успешно сохранён.');

        return redirect()->route('platform.floor.edit', $floor->id);
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

    public function test(): void
    {
        dd('WORKS');
    }
}
