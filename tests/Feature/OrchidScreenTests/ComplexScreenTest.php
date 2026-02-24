<?php

declare(strict_types=1);

namespace OrchidScreenTests;

use App\Models\Complex;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Orchid\Support\Facades\Dashboard;
use Orchid\Support\Testing\ScreenTesting;
use Tests\TestCase;

/**
 * Тест экрана ЖК.
 */
final class ComplexScreenTest extends TestCase
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
    public function test_can_view_complex_list(): void
    {
        Complex::factory()->count(3)->create();

        $this->actingAs($this->admin);

        $this->screen('platform.complex.list')
            ->display()
            ->assertSee('Жилые комплексы');
    }

    /**
     * Экран создания доступен.
     */
    public function test_complex_create_screen_is_accessible(): void
    {
        $this->actingAs($this->admin);

        $this->get(route('platform.complex.edit'))
            ->assertOk();
    }

    /**
     * Экран редактирования доступен.
     */
    public function test_complex_edit_screen_is_accessible(): void
    {
        $complex = Complex::factory()->create();

        $this->actingAs($this->admin);

        $this->get(route('platform.complex.edit', $complex))
            ->assertOk();
    }
}
