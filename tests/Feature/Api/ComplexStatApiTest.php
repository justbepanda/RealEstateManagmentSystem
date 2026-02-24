<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Enums\PremiseStatusEnum;
use App\Models\Complex;
use App\Models\Premise;
use App\Models\Floor;
use App\Models\Section;
use App\Models\Building;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * Тест API статистики по ЖК.
 */
class ComplexStatApiTest extends TestCase
{
    /**
     * Тест получения статистики по ЖК.
     */
    public function test_can_get_complex_statistics(): void
    {
        Cache::tags(['statistics', 'complexes'])->flush();

        $complex = Complex::factory()->create(['name' => 'Stat Complex']);

        $building = Building::factory()->create(['complex_id' => $complex->id]);
        $section = Section::factory()->create(['building_id' => $building->id]);
        $floor = Floor::factory()->create(['section_id' => $section->id]);

        Premise::factory()->count(3)->create([
            'floor_id' => $floor->id,
            'status' => PremiseStatusEnum::AVAILABLE->value
        ]);
        Premise::factory()->count(2)->create([
            'floor_id' => $floor->id,
            'status' => PremiseStatusEnum::SOLD->value
        ]);
        Premise::factory()->count(1)->create([
            'floor_id' => $floor->id,
            'status' => PremiseStatusEnum::RESERVED->value
        ]);

        $response = $this->getJson('/api/complexes/stats');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'premise_stat' => [
                            'total_units',
                            'available',
                            'reserved',
                            'sold',
                        ],
                    ]
                ]
            ]);
    }
}
