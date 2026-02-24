<?php

declare(strict_types=1);

namespace OrchidScreenTests;

use App\Models\Premise;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Orchid\Support\Facades\Dashboard;
use Orchid\Support\Testing\ScreenTesting;
use Tests\TestCase;

/**
 * Тест экранов Помещений.
 */
final class PremiseScreenTest extends TestCase
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
    public function test_can_view_premise_list(): void
    {
        Premise::factory()->count(3)->create();

        $this->actingAs($this->admin);

        $this->screen('platform.premise.list')
            ->display()
            ->assertOk()
            ->assertSee('Помещения');
    }

    /**
     * Экран создания доступен.
     */
    public function test_premise_create_screen_is_accessible(): void
    {
        $this->actingAs($this->admin);

        $this->get(route('platform.premise.edit'))
            ->assertOk();
    }

    /**
     * Экран редактирования доступен.
     */
    public function test_premise_edit_screen_is_accessible(): void
    {
        $premise = Premise::factory()->create();

        $this->actingAs($this->admin);

        $this->get(route('platform.premise.edit', $premise))
            ->assertOk();
    }
}
