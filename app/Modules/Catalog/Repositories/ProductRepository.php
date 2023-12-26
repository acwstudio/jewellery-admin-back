<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Repositories;

use App\Modules\Catalog\Contracts\Pipelines\ProductQueryBuilderPipelineContract;
use App\Modules\Catalog\Models\Brand;
use App\Modules\Catalog\Models\PreviewImage;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Support\Blueprints\ProductBlueprint;
use App\Modules\Catalog\Support\Pagination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductRepository
{
    public function getById(int $id, bool $fail = false): ?Product
    {
        if ($fail) {
            return Product::findOrFail($id);
        }

        return Product::find($id);
    }

    public function getByExternalId(string $externalId): ?Product
    {
        /** @var Product|null $product */
        $product = Product::query()->where('external_id', '=', $externalId)->first();
        return $product;
    }

    public function getBySku(string $sku): ?Product
    {
        /** @var Product|null $product */
        $product = Product::query()->where('sku', '=', $sku)->first();
        return $product;
    }

    public function getBySlug(string $slug, bool $fail = false): ?Product
    {
        /** @var Product|null $product */
        $product = Product::query()->where('slug', '=', $slug)->first();

        if ($fail && !$product instanceof Product) {
            throw new ModelNotFoundException();
        }

        return $product;
    }

    public function getByIds(array $ids, bool $fail = false): Collection
    {
        $products = Product::query()->whereIn('id', $ids)->get();

        if ($fail && $products->count() === 0) {
            throw new ModelNotFoundException();
        }

        return $products;
    }

    public function getBySkuList(array $skuList, bool $fail = false): Collection
    {
        $products = Product::query()
            ->without(
                ['brand', 'categories', 'previewImage', 'productOffers', 'imageUrls', 'images', 'productFeatures']
            )
            ->whereIn('sku', $skuList)
            ->get();

        if ($fail && $products->count() === 0) {
            throw new ModelNotFoundException();
        }

        return $products;
    }

    public function getList(Pagination $pagination, bool $fail = false): LengthAwarePaginator
    {
        $pagination = $this->setDefaultPagination($pagination);

        $query = Product::query();

        /** @var ProductQueryBuilderPipelineContract $pipeline */
        $pipeline = app(ProductQueryBuilderPipelineContract::class);

        /** @var LengthAwarePaginator $products */
        $products = $pipeline
            ->send($query)
            ->thenReturn()
            ->paginate($pagination->perPage, ['*'], 'page', $pagination->page);

        if ($fail && $products->total() === 0) {
            throw new ModelNotFoundException();
        }

        return $products;
    }

    public function getAll(): Collection
    {
        return Product::all();
    }

    public function getAllByPagination(Pagination $pagination, bool $fail = false): LengthAwarePaginator
    {
        $pagination = $this->setDefaultPagination($pagination);

        $products = Product::query()
            ->paginate($pagination->perPage, ['*'], 'page', $pagination->page);

        if ($fail && $products->total() === 0) {
            throw new ModelNotFoundException();
        }

        return $products;
    }

    public function create(
        ProductBlueprint $productBlueprintData,
        Collection $categories,
        PreviewImage|null $previewImage = null,
        Brand|null $brand = null,
        array $images = []
    ): Product {
        $product = new Product([
            'sku' => $productBlueprintData->sku,
            'name' => $productBlueprintData->name,
            'summary' => $productBlueprintData->summary,
            'description' => $productBlueprintData->description,
            'catalog_number' => $productBlueprintData->catalog_number,
            'supplier' => $productBlueprintData->supplier,
            'liquidity' => $productBlueprintData->liquidity,
            'stamp' => $productBlueprintData->stamp,
            'manufacture_country' => $productBlueprintData->manufacture_country,
            'meta_title' => $productBlueprintData->meta_title,
            'meta_description' => $productBlueprintData->meta_description,
            'meta_keywords' => $productBlueprintData->meta_keywords,
            'is_active' => $productBlueprintData->is_active,
            'is_drop_shipping' => $productBlueprintData->is_drop_shipping,
            'rank' => $productBlueprintData->rank,
            'popularity' => $productBlueprintData->popularity,
            'external_id' => $productBlueprintData->external_id,
            'name_1c' => $productBlueprintData->name_1c,
            'description_1c' => $productBlueprintData->description_1c,
            'slug' => $productBlueprintData->getSlug()
        ]);

        $product->previewImage()->associate($previewImage);
        $product->brand()->associate($brand);

        $product->save();
        $product->categories()->attach($categories);
        $product->images()->attach($images);

        return $product;
    }

    public function update(
        Product $product,
        ProductBlueprint $productBlueprintData,
        Collection $categories,
        PreviewImage|null $previewImage = null,
        Brand|null $brand = null,
        array $images = []
    ): Product {
        $payload = [
            'sku' => $productBlueprintData->sku,
            'name' => $productBlueprintData->name,
            'summary' => $productBlueprintData->summary,
            'description' => $productBlueprintData->description,
            'catalog_number' => $productBlueprintData->catalog_number,
            'supplier' => $productBlueprintData->supplier,
            'liquidity' => $productBlueprintData->liquidity,
            'stamp' => $productBlueprintData->stamp,
            'manufacture_country' => $productBlueprintData->manufacture_country,
            'meta_title' => $productBlueprintData->meta_title,
            'meta_description' => $productBlueprintData->meta_description,
            'meta_keywords' => $productBlueprintData->meta_keywords,
            'is_active' => $productBlueprintData->is_active,
            'is_drop_shipping' => $productBlueprintData->is_drop_shipping,
            'rank' => $productBlueprintData->rank,
            'popularity' => $productBlueprintData->popularity,
            'name_1c' => $productBlueprintData->name_1c,
            'description_1c' => $productBlueprintData->description_1c
        ];

        /** При импорте из 1С не переписывать поля "summary", "is_active" */
        if (!empty($productBlueprintData->external_id)) {
            unset($payload['summary']);
            unset($payload['is_active']);
        }

        $product->update($payload);

        $product->previewImage()->associate($previewImage);
        $product->brand()->associate($brand);

        $product->categories()->sync($categories);
        $product->images()->sync($images);

        $product->save();
        $product->refresh();

        return $product;
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    public function updateIsActive(array $ids, bool $isActive): void
    {
        Product::query()->whereIn('id', $ids)->update([
            'is_active' => $isActive
        ]);
    }

    private function setDefaultPagination(Pagination $pagination): Pagination
    {
        $page = $pagination->page ?? 1;
        $perPage = $pagination->perPage ?? config('pagination.' . Product::class . '.default_per_page');

        return new Pagination($page, $perPage);
    }
}
