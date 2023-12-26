<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Collections\Export;

use Spatie\LaravelData\Data;

class ExportCollectionData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly array $sku_list
    ) {
    }
}
