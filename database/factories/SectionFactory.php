<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Building;
use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Section>
 */
class SectionFactory extends Factory
{
    protected $model = Section::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'building_id'  => Building::factory(),
            'name'         => 'Секция ' . $this->faker->randomElement(['А', 'Б', 'В', 'Г']),
            'floors_count' => $this->faker->numberBetween(5, 25),
        ];
    }
}
