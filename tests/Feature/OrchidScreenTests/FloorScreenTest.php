<?php

declare(strict_types=1);

namespace OrchidScreenTests;

use App\Models\Floor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Orchid\Support\Facades\Dashboard;
use Orchid\Support\Testing\ScreenTesting;
use Tests\TestCase;

/**
 * Тест экранов Этажей.
 */
final class FloorScreenTest extends TestCase
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
     * Список
     */
    public function test_can_view_floor_list(): void
    {
        Floor::factory()->count(3)->create();

        $this->actingAs($this->admin);

        $this->screen('platform.floor.list')
            ->display()
            ->assertOk()
            ->assertSee('Этажи');
    }

    /**
     * Экран создания доступен.
     */
    public function test_floor_create_screen_is_accessible(): void
    {
        $this->actingAs($this->admin);

        $this->get(route('platform.floor.edit'))
            ->assertOk();
    }

    /**
     * Экран редактирования доступен.
     */
    public function test_floor_edit_screen_is_accessible(): void
    {
        $floor = Floor::factory()->create();

        $this->actingAs($this->admin);

        $this->get(route('platform.floor.edit', $floor))
            ->assertOk();
    }
}
