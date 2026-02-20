<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PremiseStatus;
use App\Enums\PremiseStatusEnum;
use App\Enums\PremiseType;
use App\Enums\PremiseTypeEnum;
use App\Models\Floor;
use App\Models\Premise;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Premise>
 */
class PremiseFactory extends Factory
{
    protected $model = Premise::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $areaTotal = $this->faker->randomFloat(2, 30, 120);
        $areaLiving = $areaTotal * 0.6;
        $areaKitchen = $areaTotal * 0.2;

        $pricePerM2 = $this->faker->numberBetween(15000000, 30000000);
        $priceBase = (int) ($pricePerM2 * $areaTotal);

        return [
            'floor_id'       => Floor::factory(),
            'number'         => (string) $this->faker->unique()->numberBetween(1, 500),
            'type'           => $this->faker->randomElement(PremiseTypeEnum::cases()),
            'status'         => $this->faker->randomElement(PremiseStatusEnum::cases()),
            'rooms'          => $this->faker->numberBetween(1, 4),

            'area_total'     => $areaTotal,
            'area_living'    => $areaLiving,
            'area_kitchen'   => $areaKitchen,

            'price_base'     => $priceBase,
            'price_discount' => $this->faker->optional(0.3)->numberBetween(
                (int)($priceBase * 0.9),
                (int)($priceBase * 0.95)
            ),
            'price_per_m2'   => $pricePerM2,

            'features'       => [
                'has_balcony' => $this->faker->boolean(),
                'view_type'   => $this->faker->randomElement(['city', 'park', 'courtyard']),
                'is_smart_home' => $this->faker->boolean(20),
            ],
        ];
    }
}
