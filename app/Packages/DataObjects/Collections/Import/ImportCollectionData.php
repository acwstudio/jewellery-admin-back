<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Collections\Import;

use App\Packages\DataCasts\BooleanCast;
use App\Packages\DataCasts\CollectionCast;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class ImportCollectionData extends Data
{
    public function __construct(
        #[MapInputName('ID')]
        public readonly string $external_id,
        public readonly string $slug,
        public readonly string $name,
        public readonly ?string $description,
        public readonly string $preview_image,
        public readonly string $preview_image_mob,
        public readonly string $banner_image,
        public readonly string $banner_image_mob,
        #[WithCast(CollectionCast::class)]
        public readonly Collection $products,
        public readonly string $extended_name,
        public readonly string $extended_description,
        public readonly string $extended_image,
        #[WithCast(BooleanCast::class)]
        public readonly bool $is_active,
        #[WithCast(BooleanCast::class)]
        public readonly bool $is_hidden
    ) {
    }
}
