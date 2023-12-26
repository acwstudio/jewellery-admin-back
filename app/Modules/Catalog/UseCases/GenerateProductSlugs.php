<?php

declare(strict_types=1);

namespace App\Modules\Catalog\UseCases;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Services\ProductService;
use App\Modules\Catalog\Support\Pagination;
use App\Modules\Catalog\Support\SlugGenerator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Psr\Log\LoggerInterface;

class GenerateProductSlugs
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly SlugGenerator $slugGenerator,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(?callable $onEach = null): void
    {
        $paginator = $this->productService->getAllProductsByPagination(new Pagination(1, 100));
        $this->updateGroupProducts(new Collection($paginator->items()), $onEach);

        while ($paginator->lastPage() > $paginator->currentPage()) {
            $paginator = $this->productService->getAllProductsByPagination(
                new Pagination($paginator->currentPage() + 1, 100)
            );
            $this->updateGroupProducts(new Collection($paginator->items()), $onEach);
        }
    }

    private function updateGroupProducts(Collection $products, ?callable $onEach = null): void
    {
        /** @var Product $product */
        foreach ($products as $product) {
            try {
                DB::transaction(function () use ($product) {
                    $this->updateProduct($product);
                });
            } catch (\Throwable $e) {
                $this->logger->error(
                    "Product with productSKU: $product->sku generate slug error",
                    ['exception' => $e]
                );
            }

            if (null !== $onEach) {
                call_user_func($onEach);
            }
        }
    }

    private function updateProduct(Product $product): void
    {
        $slug = $this->slugGenerator->createForProduct($product->name, $product->sku);
        $product->update([
            'slug' => $slug
        ]);
    }
}
