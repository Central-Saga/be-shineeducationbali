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
        $entityTypes = ['student', 'teacher', 'program', 'assignment', 'material'];
        
        $selectedEntityType = $this->faker->randomElement($entityTypes);
        $entityId = null;
        
        // Generate valid entity ID based on the entity type
        switch ($selectedEntityType) {
            case 'student':
                $entityId = Student::inRandomOrder()->first()?->id ?? 1;
                break;
            case 'teacher':
                $entityId = Teacher::inRandomOrder()->first()?->id ?? 1;
                break;
            case 'program':
                $entityId = Program::inRandomOrder()->first()?->id ?? 1;
                break;
            case 'assignment':
                $entityId = Assignment::inRandomOrder()->first()?->id ?? 1;
                break;
            case 'material':
                $entityId = Material::inRandomOrder()->first()?->id ?? 1;
                break;
        }
        
        // Generate file path based on entity type
        $fileExtensions = ['pdf', 'jpg', 'png', 'doc', 'xlsx', 'zip', 'mp4', 'pptx'];
        $randomFileName = $this->faker->uuid . '.' . $this->faker->randomElement($fileExtensions);
        $filePath = $selectedEntityType . '/' . $entityId . '/' . $randomFileName;
        
        return [
            'file_path' => $filePath,
            'size_kb' => $this->faker->numberBetween(100, 10000),
            'upload_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'description' => $this->faker->sentence(8),
            'storage_type' => $this->faker->randomElement($storageTypes),
            'entity_id' => $entityId,
            'entity_type' => $selectedEntityType,
        ];
    }

    /**
     * Indicate that the asset is for a specific entity type.
     *
     * @param string $entityType
     * @param int $entityId
     * @return static
     */
    public function forEntity(string $entityType, int $entityId): static
    {
        return $this->state(function (array $attributes) use ($entityType, $entityId) {
            return [
                'entity_type' => $entityType,
                'entity_id' => $entityId,
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
