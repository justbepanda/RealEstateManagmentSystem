<?php

declare(strict_types=1);

namespace OrchidScreenTests;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Orchid\Support\Facades\Dashboard;
use Orchid\Support\Testing\ScreenTesting;
use Tests\TestCase;

/**
 * Тест экранов истории помещений.
 */
final class PremiseHistoryScreenTest extends TestCase
{
    use ScreenTesting;

    private User $admin;

    /**
     * Настройка тестового окружения.
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
     * Проверка доступности истории изменений статусов.
     */
    public function test_premise_status_history_screen_is_accessible(): void
    {
        $this->actingAs($this->admin);

        $this->screen('platform.premises.status-history')
            ->display()
            ->assertOk()
            ->assertSee('История статусов');
    }

    /**
     * Проверка доступности истории изменений цен.
     */
    public function test_premise_price_history_screen_is_accessible(): void
    {
        $this->actingAs($this->admin);

        $this->screen('platform.premises.price-history')
            ->display()
            ->assertOk()
            ->assertSee('История цен');
    }
}
