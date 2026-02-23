<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PremiseStatusEnum;
use App\Enums\PremiseTypeEnum;
use App\Models\Premise;
use App\Models\Floor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Orchid\Attachment\File as OrchidFile;

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

        $pricePerM2 = $this->faker->numberBetween(150000, 300000);
        $priceBase = (int)($pricePerM2 * $areaTotal);

        $features = ['balcony', 'loggia', 'high_ceil', 'view_park', 'panoramic_windows', 'combined_bath'];

        return [
            'floor_id' => Floor::factory(),
            'number' => (string)$this->faker->unique()->numberBetween(1, 500),
            'type' => $this->faker->randomElement(PremiseTypeEnum::cases()),
            'status' => $this->faker->randomElement(PremiseStatusEnum::cases()),
            'rooms' => $this->faker->numberBetween(1, 4),

            'area_total' => $areaTotal,
            'area_living' => $areaLiving,
            'area_kitchen' => $areaKitchen,

            'price_base' => $priceBase,
            'price_discount' => $this->faker->optional(0.3)->numberBetween(
                (int)($priceBase * 0.9),
                (int)($priceBase * 0.95)
            ),
            'price_per_m2' => $pricePerM2,

            'features' => $this->faker->randomElements($features, rand(1, 3)),
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-1 month'),
        ];
    }

    public function configure(): self
    {
        return $this->afterCreating(function (Premise $premise): void {

            /**
             * Загружает файл в Orchid и привязывает к помещению
             */
            $attachFile = function (string $sourcePath, string $group, string $alt) use ($premise): void {

                $file = new UploadedFile(
                    $sourcePath,
                    basename($sourcePath),
                    File::mimeType($sourcePath),
                    null,
                    true
                );

                $attachment = (new OrchidFile($file))
                    ->path('premises')
                    ->load();

                $attachment->update([
                    'group' => $group,
                    'alt'   => $alt,
                ]);

                $premise->attachments()->syncWithoutDetaching([$attachment->id]);
            };

            /**
             * Планировка
             */
            $planImages = glob(database_path('seeders/demo_photos/premise/plan/*.jpg'));

            if (!empty($planImages)) {
                $planPath = $this->faker->randomElement($planImages);

                $attachFile(
                    $planPath,
                    'layout',
                    "Планировка помещения №{$premise->number}"
                );
            }

            /**
             * Галерея
             */
            $roomImages = glob(database_path('seeders/demo_photos/premise/room/*.jpg'));

            if (empty($roomImages)) {
                return;
            }

            $randomCount = $this->faker->numberBetween(2, 5);

            $selectedImages = collect($roomImages)
                ->shuffle()
                ->take($randomCount);

            foreach ($selectedImages as $index => $imagePath) {
                $photo = $index + 1;
                $attachFile(
                    $imagePath,
                    'gallery',
                    "Фото {$photo} помещения №{$premise->number}"
                );
            }
        });
    }
}
