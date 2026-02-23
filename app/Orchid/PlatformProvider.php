<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Dashboard $dashboard
     *
     * @return void
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {

        return [
            Menu::make('Жилые комплексы')
                ->icon('building')
                ->route('platform.complex.list')
                ->title('Управление объектами'),

            Menu::make('Здания')
                ->icon('house')
                ->route('platform.building.list'),

            Menu::make('Секции')
                ->icon('grid')
                ->route('platform.section.list'),

            Menu::make('Этажи')
                ->icon('layers')
                ->route('platform.floor.list'),

            Menu::make('Помещения')
                ->icon('door-closed')
                ->route('platform.premise.list'),

            Menu::make('История статусов')
                ->icon('bs.clock-history')
                ->route('platform.premises.status-history')
                ->title('Логирование'),

            Menu::make('История цен')
                ->icon('bs.currency-dollar')
                ->route('platform.premises.price-history'),

            Menu::make(__('Users'))
                ->icon('bs.people')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Access Controls')),

            Menu::make(__('Roles'))
                ->icon('bs.shield')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles')
                ->divider(),
        ];
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
        ];
    }
}
