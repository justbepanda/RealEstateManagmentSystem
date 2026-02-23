<?php

namespace Database\Factories;

use App\Models\Building;
use App\Models\Complex;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Building>
 */
class BuildingFactory extends Factory
{
    protected $model = Building::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'complex_id' => fn() => Complex::factory(),
            'name' => 'Корпус ' . $this->faker->numberBetween(1, 25) . ' ' . strtoupper($this->faker->lexify('?')),
            'floors_count' => $this->faker->numberBetween(5, 25),
            'build_year' => $this->faker->numberBetween(2024, 2028),
        ];
    }
}
