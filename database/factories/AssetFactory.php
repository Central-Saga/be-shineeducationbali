<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\Assignment;
use App\Models\Material;
use App\Models\Program;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Asset::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $storageTypes = ['local', 'cloud', 's3', 'google_drive'];
        $assetableTypes = [
            Student::class => 'App\\Models\\Student', 
            Teacher::class => 'App\\Models\\Teacher', 
            Program::class => 'App\\Models\\Program', 
            Assignment::class => 'App\\Models\\Assignment', 
            Material::class => 'App\\Models\\Material'
        ];
        
        $selectedAssetableClass = $this->faker->randomElement(array_keys($assetableTypes));
        $assetableType = $assetableTypes[$selectedAssetableClass];
        $assetableId = $selectedAssetableClass::inRandomOrder()->first()?->id ?? 1;
        
        // Generate file path based on assetable type
        $fileExtensions = ['pdf', 'jpg', 'png', 'doc', 'xlsx', 'zip', 'mp4', 'pptx'];
        $randomFileName = $this->faker->uuid . '.' . $this->faker->randomElement($fileExtensions);
        $assetableName = strtolower(class_basename($selectedAssetableClass));
        $filePath = $assetableName . '/' . $assetableId . '/' . $randomFileName;
        
        return [
            'file_path' => $filePath,
            'size_kb' => $this->faker->numberBetween(100, 10000),
            'upload_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'description' => $this->faker->sentence(8),
            'storage_type' => $this->faker->randomElement($storageTypes),
            'assetable_id' => $assetableId,
            'assetable_type' => $assetableType,
        ];
    }

    /**
     * Indicate that the asset is for a specific assetable entity.
     *
     * @param string $assetableType
     * @param int $assetableId
     * @return static
     */
    public function forAssetable(string $assetableType, int $assetableId): static
    {
        return $this->state(function (array $attributes) use ($assetableType, $assetableId) {
            return [
                'assetable_type' => $assetableType,
                'assetable_id' => $assetableId,
            ];
        });
    }

    /**
     * Indicate that the asset is stored locally.
     *
     * @return static
     */
    public function localStorage(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'storage_type' => 'local',
            ];
        });
    }
}
