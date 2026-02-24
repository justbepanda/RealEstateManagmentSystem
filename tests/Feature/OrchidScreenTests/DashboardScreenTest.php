<?php

declare(strict_types=1);

namespace OrchidScreenTests;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Orchid\Support\Facades\Dashboard;
use Orchid\Support\Testing\ScreenTesting;
use Tests\TestCase;

/**
 * Тест главного экрана панели управления.
 */
final class DashboardScreenTest extends TestCase
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
     * Проверка доступности главной страницы.
     */
    public function test_main_dashboard_screen_is_accessible(): void
    {
        $this->actingAs($this->admin);

        $this->screen('platform.main')
            ->display()
            ->assertOk()
            ->assertSee('Панель управления');
    }
}
