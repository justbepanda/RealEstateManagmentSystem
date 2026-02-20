<?php

namespace Database\Factories;

use App\Enums\ComplexStatusEnum;
use App\Models\Complex;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Complex>
 */
class ComplexFactory extends Factory
{
    protected $model = Complex::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company() . ' Residence',
            'description' => $this->faker->paragraph(),
            'address' => $this->faker->address(),
            'status' => $this->faker->randomElement(ComplexStatusEnum::cases()),
            'latitude'    => $this->faker->latitude(),
            'longitude'   => $this->faker->longitude(),
        ];
    }
}
