<?php

declare(strict_types=1);

namespace App\Modules\Collections\Support\Blueprints;

class FavoriteBlueprint
{
    public function __construct(
        public readonly string $slug,
        public readonly string $name,
        public readonly string $description,
        public readonly string $background_color,
        public readonly ?string $font_color = null
    ) {
    }
}
