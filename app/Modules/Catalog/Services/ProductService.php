<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\Models\Brand;
use App\Modules\Catalog\Models\PreviewImage;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Repositories\BrandRepository;
use App\Modules\Catalog\Repositories\CategoryRepository;
use App\Modules\Catalog\Repositories\PreviewImageRepository;
use App\Modules\Catalog\Repositories\ProductRepository;
use App\Modules\Catalog\Support\Blueprints\ProductBlueprint;
use App\Modules\Catalog\Support\Filters\CategoryFilter;
use App\Modules\Catalog\Support\Pagination;
use App\Modules\Catalog\Support\SlugGenerator;
use App\Packages\Events\ProductCreated;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProductService
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly CategoryRepository $categoryRepository,
        private readonly PreviewImageRepository $previewImageRepository,
        private readonly BrandRepository $brandRepository,
        private readonly SlugGenerator $slugGenerator,
    ) {
    }

    public function getProduct(int $id): ?Product
    {
        return $this->productRepository->getById($id);
    }

    public function getProductBySlug(string $slug): ?Product
    {
        return $this->productRepository->getBySlug($slug);
    }

    public function getProducts(Pagination $pagination): LengthAwarePaginator
    {
        return $this->productRepository->getList($pagination);
    }

    public function getAllProducts(): Collection
    {
        return $this->productRepository->getAll();
    }

    public function getAllProductsByPagination(Pagination $pagination): LengthAwarePaginator
    {
        return $this->productRepository->getAllByPagination($pagination);
    }

    /**
     * @param array $ids
     * @return \Illuminate\Support\Collection<Product>
     */
    public function getProductByIds(array $ids): Collection
    {
        return $this->productRepository->getByIds($ids);
    }

    /**
     * @param array $skuList
     * @return \Illuminate\Support\Collection<Product>
     */
    public function getProductBySkuList(array $skuList): Collection
    {
        return $this->productRepository->getBySkuList($skuList);
    }

    public function getProductByExternalId(string $externalId): ?Product
    {
        return $this->productRepository->getByExternalId($externalId);
    }

    public function getProductBySku(string $externalId): ?Product
    {
        return $this->productRepository->getBySku($externalId);
    }

    public function createProduct(
        ProductBlueprint $productBlueprint,
        array $categoryIds,
        PreviewImage|int|null $previewImage = null,
        Brand|int|null $brand = null,
        array $images = []
    ): Product {
        $categories = $this->categoryRepository->getCategories(
            new CategoryFilter(id: new Collection($categoryIds)),
            true
        );

        if (is_int($previewImage)) {
            $previewImage = $this->previewImageRepository->getById($previewImage, true);
        }

        if (is_int($brand)) {
            $brand = $this->brandRepository->getById($brand, true);
        }

        $images = $this->getPreviewImageArrayWithOrder($images);

        if (empty($productBlueprint->getSlug())) {
            $productBlueprint->setSlug(
                $this->slugGenerator->createForProduct($productBlueprint->name, $productBlueprint->sku)
            );
        }

        $product = $this->productRepository->create(
            $productBlueprint,
            $categories,
            $previewImage,
            $brand,
            $images
        );

        ProductCreated::dispatch($product->id);

        return $product;
    }

    public function updateProduct(
        Product|int $product,
        ProductBlueprint $productBlueprint,
        array $categoryIds,
        PreviewImage|int|null $previewImage = null,
        Brand|int|null $brand = null,
        array $images = []
    ): Product {
        if (is_int($product)) {
            $product = $this->productRepository->getById($product, true);
        }

        $categories = $this->categoryRepository->getCategories(
            new CategoryFilter(id: new Collection($categoryIds)),
            true
        );

        if (is_int($previewImage)) {
            $previewImage = $this->previewImageRepository->getById($previewImage, true);
        }

        if (is_int($brand)) {
            $brand = $this->brandRepository->getById($brand, true);
        }

        $images = $this->getPreviewImageArrayWithOrder($images);

        return $this->productRepository->update(
            $product,
            $productBlueprint,
            $categories,
            $previewImage,
            $brand,
            $images
        );
    }

    public function deleteProduct(int $id): void
    {
        $product = $this->productRepository->getById($id, true);
        $this->productRepository->delete($product);
    }

    public function updateProductIsActive(array $ids, bool $isActive): void
    {
        $this->productRepository->updateIsActive($ids, $isActive);
    }

    private function getPreviewImageArrayWithOrder(array $ids): array
    {
        $previewImages = $this->previewImageRepository->getByIds($ids);
        $images = [];
        foreach ($previewImages as $previewImage) {
            $key = (int)array_search($previewImage->getKey(), $ids);
            $images[$previewImage->getKey()] = ['order_column' => $key + 1];
        }

        return $images;
    }
}
