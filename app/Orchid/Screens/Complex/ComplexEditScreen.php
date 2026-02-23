<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Complex;

use App\Enums\ComplexStatusEnum;
use App\Models\Complex;
use App\Orchid\Layouts\Complex\ComplexEditLayout;
use DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rules\Enum;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Throwable;

/**
 * Экран создания и редактирования конкретного ЖК.
 */
final class ComplexEditScreen extends Screen
{
    /**
     * @var Complex|null
     */
    public ?Complex $complex = null;

    /**
     * Загружает данные экрана.
     */
    public function query(?Complex $complex = null): array
    {
        if ($complex?->exists) {
            $this->complex = $complex;
        } else {
            $this->complex = new Complex();
        }


        return [
            'complex' => $this->complex,
        ];
    }

    /**
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->complex->exists ? 'Редактировать ЖК' : 'Создать новый ЖК';
    }

    /**
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
                ->canSee($this->complex->exists),
        ];
    }

    /**
     * @return Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            ComplexEditLayout::class,
        ];
    }

    /**
     * Сохранение записи.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws Throwable
     */

    public function save(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'complex.name'        => 'required|string|max:255',
            'complex.address'     => 'required|string|max:255',
            'complex.status'      => ['required', new Enum(ComplexStatusEnum::class)],
            'complex.description' => 'nullable|string',
            'complex.attachments' => 'nullable|array',
            'complex_map.lat'     => 'required|numeric|between:-90,90',
            'complex_map.lng'     => 'required|numeric|between:-180,180',
        ]);

        $complex = $this->complex ?? new Complex();

        DB::transaction(function () use ($validated, $request, $complex) {
            $complex->fill($validated['complex']);
            $complex->latitude  = $request->input('complex_map.lat');
            $complex->longitude = $request->input('complex_map.lng');
            $complex->save();

            $complex->attachments()->sync($request->input('complex.attachments', []));

            Cache::tags(['complexes', 'references', 'statistics'])->flush();
        });

        Alert::info('ЖК успешно сохранён.');

        return redirect()->route('platform.complex.edit', $complex->id);
    }

    /**
     * Удаление комплекса.
     *
     * @return RedirectResponse
     * @throws Throwable
     */
    public function remove(): RedirectResponse
    {
        DB::transaction(function () {
            $this->complex->attachments()->detach();
            $this->complex->delete();
        });

        Cache::tags(['complexes', 'references', 'statistics'])->flush();

        Alert::info('ЖК успешно удалён.');

        return redirect()->route('platform.complex.list');
    }
}
