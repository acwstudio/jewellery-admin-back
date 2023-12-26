<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Collections\Stone;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'collections_create_stone_data',
    description: 'Создание вставки (камня) коллекции',
    required: ['name'],
    type: 'object'
)]
class CreateStoneData extends Data
{
    public function __construct(
        #[Property(property: 'name', type: 'string')]
        public readonly string $name,
    ) {
    }
}
