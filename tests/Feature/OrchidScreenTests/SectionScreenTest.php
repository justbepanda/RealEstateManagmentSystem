<?php

declare(strict_types=1);

namespace OrchidScreenTests;

use App\Models\Section;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Orchid\Support\Facades\Dashboard;
use Orchid\Support\Testing\ScreenTesting;
use Tests\TestCase;

/**
 * Тест экранов Секций.
 */
final class SectionScreenTest extends TestCase
{
    use ScreenTesting;

    private User $admin;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'permissions' => Dashboard::getAllowAllPermission(),
            'password' => Hash::make('password'),
        ]);
    }

    /**
     * Просмотр списка.
     */
    public function test_can_view_section_list(): void
    {
        Section::factory()->count(3)->create();

        $this->actingAs($this->admin);

        $this->screen('platform.section.list')
            ->display()
            ->assertOk()
            ->assertSee('Секции');
    }

    /**
     * Экран создания доступен.
     */
    public function test_section_create_screen_is_accessible(): void
    {
        $this->actingAs($this->admin);

        $this->get(route('platform.section.edit'))
            ->assertOk();
    }

    /**
     * Экран редактирования доступен.
     */
    public function test_section_edit_screen_is_accessible(): void
    {
        $section = Section::factory()->create();

        $this->actingAs($this->admin);

        $this->get(route('platform.section.edit', $section))
            ->assertOk();
    }
}
