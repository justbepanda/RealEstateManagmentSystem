<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Complex;
use App\Models\Floor;
use App\Models\Premise;
use App\Models\Section;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name'        => 'Admin',
            'email'       => 'admin@admin.com',
            'password'    => Hash::make('password'),
            'permissions' => [
                'platform.index' => true,
                'platform.systems.roles' => true,
                'platform.systems.users' => true,
                'platform.systems.attachment' => true,
            ],
        ]);

        $complexes = Complex::factory(3)->create();
        $buildings = Building::factory(4)->recycle($complexes)->create();
        $sections = Section::factory(8)->recycle($buildings)->create();
        $floors = Floor::factory(40)->recycle($sections)->recycle($buildings)->create();
        Premise::factory(160)->recycle($floors)->create();
    }
}
