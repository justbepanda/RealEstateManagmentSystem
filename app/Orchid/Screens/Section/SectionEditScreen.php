<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Section;

use App\Models\Building;
use App\Models\Section;
use App\Orchid\Layouts\Section\SectionEditLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use DB;

/**
 * Экран создания и редактирования секции.
 */
final class SectionEditScreen extends Screen
{
    /**
     * @var Section|null
     */
    public ?Section $section = null;

    /**
     * Загружает данные экрана.
     */
    public function query(?Section $section = null): array
    {
        if ($section?->exists) {
            $this->section = $section;
        } else {
            $this->section = new Section();
        }

        $complexId = $this->section->exists ? $this->section->building?->complex_id : null;

        return [
            'section' => $this->section,
            'complex_id' => $complexId,
        ];
    }

    /**
     * Заголовок экрана.
     */
    public function name(): ?string
    {
        return $this->section->exists ? 'Редактировать секцию' : 'Создать новую секцию';
    }

    /**
     * Кнопки командной панели.
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
                ->canSee($this->section->exists),
        ];
    }

    /**
     * Layout экрана.
     */
    public function layout(): array
    {
        return [
            SectionEditLayout::class,
        ];
    }

    /**
     * Сохранение секции.
     */
    public function save(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'section.building_id' => 'required|exists:buildings,id',
            'section.name'         => 'required|string|max:255',
            'section.floors_count' => 'required|integer|min:1',
        ]);

        $section = $this->section ?? new Section();

        DB::transaction(function () use ($section, $validated) {
            $section->fill($validated['section']);
            $section->save();

            Cache::tags(['sections', 'references', 'statistics'])->flush();
        });

        Alert::info('Секция успешно сохранена.');

        return redirect()->route('platform.section.edit', $section->id);
    }

    /**
     * Удаление секции.
     */
    public function remove(Section $section): RedirectResponse
    {
        DB::transaction(function () use ($section) {
            $section->delete();
            Cache::tags(['sections', 'references', 'statistics'])->flush();
        });

        Alert::info('Секция успешно удалена.');

        return redirect()->route('platform.section.list');
    }
}
