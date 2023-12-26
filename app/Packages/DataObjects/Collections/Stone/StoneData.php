<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Collections\Stone;

use App\Modules\Collections\Models\Stone;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(schema: 'collections_stone_data', type: 'object')]
class StoneData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'name', type: 'string')]
        public readonly string $name
    ) {
    }

    public static function fromModel(Stone $stone): self
    {
        return new self(
            $stone->id,
            $stone->name
        );
    }
}
