<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Premise;
use App\Models\Floor;
use App\Models\Section;
use App\Models\Building;
use App\Models\Complex;
use Tests\TestCase;

/**
 * Тест API помещений.
 */
class PremiseApiTest extends TestCase
{
    /**
     * Тест списка
     */
    public function test_can_get_premises_list(): void
    {
        Premise::factory()->count(3)->create();

        $response = $this->getJson('/api/premises');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'number',
                        'type',
                        'status',
                        'prices' => ['base', 'discount', 'per_m2', 'currency'],
                        'areas'  => ['total', 'living', 'kitchen'],
                        'images',
                        'floor_id'
                    ]
                ],
                'meta',
                'links'
            ]);
    }

    /**
     * Тест детальной информации
     */
    public function test_can_show_premise_detail_with_location(): void
    {
        $complex  = Complex::factory()->create(['name' => 'ЖК Титаны']);
        $building = Building::factory()->create(['complex_id' => $complex->id, 'name' => 'Корпус 1']);
        $section  = Section::factory()->create(['building_id' => $building->id, 'name' => 'Секция А']);
        $floor    = Floor::factory()->create(['section_id' => $section->id, 'number' => 5]);
        $premise  = Premise::factory()->create(['floor_id' => $floor->id, 'number' => '101']);

        $response = $this->getJson("/api/premises/{$premise->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.number', '101')
            ->assertJsonPath('data.location.complex', 'ЖК Титаны')
            ->assertJsonPath('data.location.building', 'Корпус 1')
            ->assertJsonPath('data.location.floor', 5);
    }

    /**
     * Тест фильтрации по количеству комнат.
     */
    public function test_can_filter_premises_by_rooms(): void
    {
        Premise::factory()->create(['rooms' => 1]);
        Premise::factory()->create(['rooms' => 3]);

        $response = $this->getJson('/api/premises?rooms=3');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.rooms', 3);
    }

    /**
     * Тест пагинации.
     */
    public function test_premises_pagination_works(): void
    {
        Premise::factory()->count(20)->create();

        $response = $this->getJson('/api/premises?per_page=10');

        $response->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonPath('meta.per_page', 10);
    }
}
