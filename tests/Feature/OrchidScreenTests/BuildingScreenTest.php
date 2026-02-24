<?php

declare(strict_types=1);

namespace OrchidScreenTests;

use App\Models\Building;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Orchid\Support\Facades\Dashboard;
use Orchid\Support\Testing\ScreenTesting;
use Tests\TestCase;

/**
 * Тест экрана Зданий.
 */
final class BuildingScreenTest extends TestCase
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
     * Просмотр списка зданий.
     */
    public function test_can_view_building_list(): void
    {
        Building::factory()->count(3)->create();

        $this->actingAs($this->admin);

        $this->screen('platform.building.list')
            ->display()
            ->assertOk()
            ->assertSee('Здания');
    }

    /**
     * Экран создания здания доступен.
     */
    public function test_building_create_screen_is_accessible(): void
    {
        $this->actingAs($this->admin);

        $this->get(route('platform.building.edit'))
            ->assertOk();
    }

    /**
     * Экран редактирования конкретного здания доступен.
     */
    public function test_building_edit_screen_is_accessible(): void
    {
        $building = Building::factory()->create();

        $this->actingAs($this->admin);

        $this->get(route('platform.building.edit', $building))
            ->assertOk();
    }
}
