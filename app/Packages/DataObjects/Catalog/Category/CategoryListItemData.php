<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Category;

use App\Modules\Catalog\Enums\CategoryListOptionsEnum;
use App\Modules\Catalog\Models\CategoryListItem;
use App\Modules\Catalog\Models\PreviewImage;
use App\Packages\DataObjects\Catalog\PreviewImage\PreviewImageData;
use Illuminate\Support\Collection;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'category_list_item_data',
    description: 'Category list item',
    type: 'object'
)]
class CategoryListItemData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'parent_id', type: 'integer')]
        public readonly ?int $parent_id,
        #[Property(property: 'title', type: 'string')]
        public readonly string $title,
        #[Property(property: 'h1', type: 'string')]
        public readonly string $h1,
        #[Property(
            property: 'children',
            type: 'array',
            items: new Items(
                ref: '#/components/schemas/category_list_item_data',
            ),
            nullable: true
        )]
        public readonly Collection $children,
        #[Property(property: 'slug', type: 'string', nullable: true)]
        public readonly string $slug,
        #[Property(
            property: 'preview_image',
            ref: '#/components/schemas/catalog_preview_image_data',
            type: 'object',
            nullable: true
        )]
        public readonly ?PreviewImageData $preview_image = null,
    ) {
    }

    public static function fromModel(CategoryListItem $categoryListItem, CategoryListOptionsData $options = null): self
    {
        $children = new Collection();

        if (in_array(CategoryListOptionsEnum::CHILDREN, $options->with)) {
            foreach ($categoryListItem->children as $child) {
                $children->add(self::fromModel($child, $options));
            }
        }

        return new self(
            $categoryListItem->id,
            $categoryListItem->parent_id,
            $categoryListItem->title,
            $categoryListItem->h1,
            $children,
            $categoryListItem->slug,
            self::getPreviewImageData($categoryListItem)
        );
    }

    private static function getPreviewImageData(CategoryListItem $categoryListItem): ?PreviewImageData
    {
        if ($categoryListItem->previewImage instanceof PreviewImage) {
            return PreviewImageData::fromModel($categoryListItem->previewImage);
        }

        return null;
    }
}
