<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Category;

use App\Modules\Catalog\Enums\CategoryOptionsEnum;
use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\PreviewImage;
use App\Packages\DataObjects\Catalog\Category\Slug\CategorySlugAliasData;
use App\Packages\DataObjects\Catalog\PreviewImage\PreviewImageData;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(schema: 'category_data', type: 'object')]
class CategoryData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'title', type: 'string')]
        public readonly string $title,
        #[Property(property: 'h1', type: 'string')]
        public readonly string $h1,
        #[Property(property: 'description', type: 'string')]
        public readonly string $description,
        #[Property(property: 'created_at', type: 'string')]
        public readonly Carbon $created_at,
        #[Property(property: 'updated_at', type: 'string')]
        public readonly Carbon $updated_at,
        #[Property(property: 'slug', type: 'string', nullable: true)]
        public readonly string $slug,
        #[Property(property: 'meta_title', type: 'string', nullable: true)]
        public readonly ?string $meta_title = null,
        #[Property(property: 'meta_description', type: 'string', nullable: true)]
        public readonly ?string $meta_description = null,
        #[Property(property: 'meta_keywords', type: 'string', nullable: true)]
        public readonly ?string $meta_keywords = null,
        #[Property(property: 'parent_id', type: 'integer', nullable: true)]
        public readonly ?int $parent_id = null,
        #[Property(
            property: 'children',
            type: 'array',
            items: new Items(
                ref: '#/components/schemas/category_data',
            ),
            nullable: true
        )]
        public readonly ?Collection $children = null,
        #[Property(
            property: 'aliases',
            type: 'array',
            items: new Items(
                ref: '#/components/schemas/category_alias_data',
            ),
            nullable: true
        )]
        public readonly ?Collection $slug_aliases = null,
        #[Property(property: 'external_id', type: 'string', nullable: true)]
        public readonly ?string $external_id = null,
        #[Property(
            property: 'preview_image',
            ref: '#/components/schemas/catalog_preview_image_data',
            type: 'object',
            nullable: true
        )]
        public readonly ?PreviewImageData $preview_image = null,
    ) {
    }

    public static function fromModel(Category $category, ?CategoryOptionsData $options = null): self
    {
        $children = null;
        $slugAliases = null;

        if (in_array(CategoryOptionsEnum::CHILDREN, $options?->with ?? [])) {
            $children = new Collection();
            foreach ($category->children as $child) {
                $children->add(self::fromModel($child, $options));
            }
        }

        if (in_array(CategoryOptionsEnum::SLUG_ALIASES, $options?->with ?? [])) {
            $slugAliases = new Collection();
            foreach ($category->slugAliases as $alias) {
                $slugAliases->add(CategorySlugAliasData::fromModel($alias));
            }
        }

        return new self(
            $category->id,
            $category->title,
            $category->h1,
            $category->description,
            $category->created_at,
            $category->updated_at,
            $category->slug,
            $category->meta_title,
            $category->meta_description,
            $category->meta_keywords,
            $category->parent?->id,
            $children,
            $slugAliases,
            $category->external_id,
            self::getPreviewImageData($category)
        );
    }

    private static function getPreviewImageData(Category $category): ?PreviewImageData
    {
        if ($category->previewImage instanceof PreviewImage) {
            return PreviewImageData::fromModel($category->previewImage);
        }

        return null;
    }
}
