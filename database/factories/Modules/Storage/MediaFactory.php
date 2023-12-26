<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Storage;

use App\Modules\Storage\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Media>
 */
class MediaFactory extends Factory
{
    protected $model = Media::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'model_type' => Model::class,
            'model_id' => rand(1, 100),
            'name' => 'image',
            'file_name' => 'image.jpg',
            'disk' => 'local',
            'size' => 1000,
            'collection_name' => 'default',
            'manipulations' => '{}',
            'custom_properties' => '{}',
            'generated_conversions' => '{}',
            'responsive_images' => '{}',
        ];
    }
}
