<?php

declare(strict_types=1);

use App\Orchid\Screens\Building\BuildingEditScreen;
use App\Orchid\Screens\Building\BuildingListScreen;
use App\Orchid\Screens\Complex\ComplexEditScreen;
use App\Orchid\Screens\Complex\ComplexListScreen;
use App\Orchid\Screens\DashboardScreen;
use App\Orchid\Screens\Floor\FloorEditScreen;
use App\Orchid\Screens\Floor\FloorListScreen;
use App\Orchid\Screens\Premise\PremiseEditScreen;
use App\Orchid\Screens\Premise\PremiseListScreen;
use App\Orchid\Screens\PremiseHistory\PremisePriceHistoryScreen;
use App\Orchid\Screens\PremiseHistory\PremiseStatusHistoryScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\Section\SectionEditScreen;
use App\Orchid\Screens\Section\SectionListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', DashboardScreen::class)
    ->name('platform.main');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Profile'), route('platform.profile')));

// Platform > System > Users > User
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(fn (Trail $trail, $user) => $trail
        ->parent('platform.systems.users')
        ->push($user->name, route('platform.systems.users.edit', $user)));

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.create')));

// Platform > System > Users
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Users'), route('platform.systems.users')));

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(fn (Trail $trail, $role) => $trail
        ->parent('platform.systems.roles')
        ->push($role->name, route('platform.systems.roles.edit', $role)));

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Create'), route('platform.systems.roles.create')));

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Roles'), route('platform.systems.roles')));

Route::screen('complexes', ComplexListScreen::class)
    ->name('platform.complex.list');
Route::screen('complex/{complex?}', ComplexEditScreen::class)
    ->name('platform.complex.edit');

Route::screen('buildings', BuildingListScreen::class)
    ->name('platform.building.list');
Route::screen('building/{building?}', BuildingEditScreen::class)
    ->name('platform.building.edit');

Route::screen('sections', SectionListScreen::class)
    ->name('platform.section.list');
Route::screen('section/{section?}', SectionEditScreen::class)
    ->name('platform.section.edit');

Route::screen('floors', FloorListScreen::class)
    ->name('platform.floor.list');
Route::screen('floor/{floor?}', FloorEditScreen::class)
    ->name('platform.floor.edit');

Route::screen('premises', PremiseListScreen::class)
    ->name('platform.premise.list');
Route::screen('premise/{premise?}', PremiseEditScreen::class)
    ->name('platform.premise.edit');

Route::screen('premises-status-history', PremiseStatusHistoryScreen::class)
    ->name('platform.premises.status-history');

Route::screen('premises-price-history', PremisePriceHistoryScreen::class)
->name('platform.premises.price-history');
