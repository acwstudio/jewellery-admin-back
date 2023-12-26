<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Product\VideoUrl;

use Spatie\LaravelData\Data;

class ImportProductVideoUrlData extends Data
{
    public function __construct(
        public readonly string $path
    ) {
    }
}
