<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Premise;

use App\Models\Premise;
use App\Orchid\Layouts\Premise\PremiseEditLayout;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Экран создания и редактирования помещения.
 */
final class PremiseEditScreen extends Screen
{
    /**
     * Текущее помещение.
     *
     * @var Premise|null
     */
    public ?Premise $premise = null;

    /**
     * Загружает данные экрана.
     *
     * @param Premise|null $premise
     * @return array<string, mixed>
     */
    public function query(?Premise $premise = null): array
    {
        $this->premise = $premise?->exists ? $premise : new Premise();

        $floor = $premise->floor;
        $section = $floor?->section;
        $building = $floor?->building ?? $section?->building;
        $complexId = $building?->complex_id;

        return [
            'premise' => $premise,
            'complex_id' => $complexId,
            'building_id' => $building?->id,
            'section_id' => $section?->id,
        ];
    }

    /**
     * Заголовок экрана.
     */
    public
    function name(): ?string
    {
        return $this->premise->exists ? 'Редактировать помещение' : 'Создать новое помещение';
    }

    /**
     * Кнопки командной панели.
     */
    public
    function commandBar(): array
    {
        return [
            Button::make('Сохранить')
                ->icon('check')
                ->method('save'),

            Button::make('Удалить')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->premise->exists),
        ];
    }

    /**
     * Layout экрана.
     */
    public
    function layout(): array
    {
        return [
            PremiseEditLayout::class,
        ];
    }

    /**
     * Сохранение помещения.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function save(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'premise.floor_id'        => ['required', 'exists:floors,id'],
            'premise.number'          => ['required', 'string', 'max:255'],
            'premise.type'            => ['required', 'string'],
            'premise.status'          => ['required', 'string'],
            'premise.rooms'           => ['required', 'integer', 'min:0'],
            'premise.area_total'      => ['required', 'numeric', 'min:0'],
            'premise.area_living'     => ['required', 'numeric', 'min:0'],
            'premise.area_kitchen'    => ['required', 'numeric', 'min:0'],
            'premise.price_base'      => ['required', 'integer', 'min:0'],
            'premise.price_discount'  => ['nullable', 'integer', 'min:0'],
            'premise.attachments'     => 'nullable|array',
            'premise.features'        => ['nullable', 'array'],
        ]);

        $data = $validated['premise'];

        $premise = $this->premise ?? new Premise();

        DB::transaction(function () use ($premise, $data): void {
            $premise->fill($data);
            $premise->save();
        });

        Alert::info('Помещение успешно сохранено.');

        return redirect()->route('platform.premise.edit', $premise->id);
    }

    /**
     * Удаление помещения.
     *
     * @param Premise $premise
     * @return RedirectResponse
     * @throws Throwable
     */
    public
    function remove(Premise $premise): RedirectResponse
    {
        DB::transaction(function () use ($premise): void {
            $premise->delete();
        });

        Alert::info('Помещение успешно удалено.');

        return redirect()->route('platform.premise.list');
    }
}
