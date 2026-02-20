<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Building;
use App\Models\Floor;
use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Floor>
 */
class FloorFactory extends Factory
{
    protected $model = Floor::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'building_id'    => Building::factory(),
            'section_id'     => Section::factory(),
            'number'         => $this->faker->numberBetween(1, 25),
            'premises_count' => 0,
        ];
    }
}
