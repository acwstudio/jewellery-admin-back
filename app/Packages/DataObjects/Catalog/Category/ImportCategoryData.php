<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Category;

use Spatie\LaravelData\Data;

class ImportCategoryData extends Data
{
    public function __construct(
        public readonly string $title,
        public readonly string $h1,
        public readonly string $description,
        public readonly ?string $meta_title = null,
        public readonly ?string $meta_description = null,
        public readonly ?string $meta_keywords = null,
        public readonly ?string $external_parent_id = null,
        public readonly ?string $external_id = null,
        public readonly ?string $slug = null
    ) {
    }
}
