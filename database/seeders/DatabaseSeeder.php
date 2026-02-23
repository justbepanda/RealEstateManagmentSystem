<?php

namespace Database\Seeders;

use App\Enums\PremiseStatusEnum;
use App\Models\Building;
use App\Models\Complex;
use App\Models\Floor;
use App\Models\Premise;
use App\Models\PremiseHistory;
use App\Models\Section;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Создание админа
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'permissions' => [
                'platform.index' => true,
                'platform.systems.roles' => true,
                'platform.systems.users' => true,
                'platform.systems.attachment' => true,
            ],
        ]);

        // Создание структуры
        $complexes = Complex::factory(3)->create();
        $buildings = Building::factory(8)->recycle($complexes)->create();
        $sections = Section::factory(8)->recycle($buildings)->create();
        $floors = Floor::factory(40)->recycle($sections)->recycle($buildings)->create();

        $premises = Premise::factory(160)->recycle($floors)->create();

        // Обновляем счетчики на этажах
        Floor::query()->each(function (Floor $floor): void {
            $floor->updateQuietly([
                'premises_count' => $floor->premises()->count()
            ]);
        });

        // Генерация истории изменений
        Premise::query()->each(function (Premise $premise): void {
            $statuses = PremiseStatusEnum::cases();
            $currentPrice = $premise->price_base;

            $currentStatus = $premise->status instanceof PremiseStatusEnum
                ? $premise->status->value
                : (string) $premise->status;

            $history = [];
            $date = Carbon::now()->subMonths(6);

            for ($i = 0; $i < 10; $i++) {

                if ($currentStatus === PremiseStatusEnum::SOLD->value) {
                    break;
                }

                $type = fake()->randomElement(['status', 'price']);

                if ($type === 'price') {
                    $old = $currentPrice;
                    $delta = (int)($currentPrice * fake()->randomFloat(2, -0.05, 0.05));
                    $currentPrice = max(1000000, $currentPrice + $delta);
                    $new = $currentPrice;
                } else {
                    $old = $currentStatus;
                    do {
                        $new = fake()->randomElement($statuses)->value;
                    } while ($new === $old);

                    $currentStatus = $new;
                }

                $history[] = [
                    'id'         => (string)Str::ulid(),
                    'premise_id' => $premise->id,
                    'user_id'    => 1,
                    'type'       => $type,
                    'old_value'  => (string)$old,
                    'new_value'  => (string)$new,
                    'created_at' => $date->copy(),
                ];

                $date->addDays(fake()->numberBetween(3, 12));

                if ($date->isFuture()) {
                    break;
                }
            }

            $premise->updateQuietly([
                'status'     => (string) $currentStatus,
                'price_base' => $currentPrice,
            ]);

            PremiseHistory::insert($history);
        });
    }
}
