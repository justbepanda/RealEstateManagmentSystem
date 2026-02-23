<?php

declare(strict_types=1);

namespace Tests\Feature\Complex;

use App\Enums\ComplexStatusEnum;
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
     * Настройка тестового окружения.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'permissions' => Dashboard::getAllowAllPermission(), // полный доступ
            'password' => Hash::make('password'),
        ]);
    }

    /**
     * Просмотр списка ЖК.
     */
    public function test_can_view_complex_list(): void
    {
        Complex::factory()->count(3)->create();

        $screen = $this->screen('platform.complex.list')
            ->actingAs($this->admin);

        $screen->display()
            ->assertSee('Жилые комплексы')
            ->assertSee(Complex::first()->name);
    }
}
