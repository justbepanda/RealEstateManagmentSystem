<?php

namespace Database\Factories;

use App\Enums\ComplexStatusEnum;
use App\Models\Complex;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Orchid\Attachment\File as OrchidFile;

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
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
        ];
    }

    /**
     * Configure the factory.
     *
     * @return static
     */
    public function configure(): self
    {
        return $this->afterCreating(function (Complex $complex) {
            $path = database_path('seeders/demo_photos/complex/*.jpg');
            $allImages = glob($path);

            if (empty($allImages)) {
                return;
            }

            $selectedImages = $this->faker->randomElements($allImages, rand(3, 5));
            $attachmentIds = [];

            foreach ($selectedImages as $sourcePath) {
                $file = new UploadedFile(
                    $sourcePath,
                    basename($sourcePath),
                    File::mimeType($sourcePath),
                    null,
                    true
                );

                $attachment = new OrchidFile($file)
                    ->path('complexes')
                    ->load();

                $attachment->update([
                    'group' => 'photo',
                    'alt'   => 'Фото ЖК ' . $complex->name,
                ]);

                $attachmentIds[] = $attachment->id;
            }

            $complex->attachments()->sync($attachmentIds);
        });
    }
}
