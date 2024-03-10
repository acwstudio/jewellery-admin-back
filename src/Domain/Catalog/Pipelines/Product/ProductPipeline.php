<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\Product;

use Domain\AbstractPipeline;
use Domain\Catalog\Pipelines\Product\Pipes\ProductDestroyPipe;
use Domain\Catalog\Pipelines\Product\Pipes\ProductsBlogPostsStoreUpdateRelationshipsPipe;
use Domain\Catalog\Pipelines\Product\Pipes\ProductSizesStoreUpdateRelationshipsPipe;
use Domain\Catalog\Pipelines\Product\Pipes\ProductsProductCategoryStoreUpdateRelationshipsPipe;
use Domain\Catalog\Pipelines\Product\Pipes\ProductsSizeCategoriesStoreUpdateRelationshipsPipe;
use Domain\Catalog\Pipelines\Product\Pipes\ProductStorePipe;
use Domain\Catalog\Pipelines\Product\Pipes\ProductsWeavesStoreUpdateRelationshipsPipe;
use Domain\Catalog\Pipelines\Product\Pipes\ProductUpdatePipe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class ProductPipeline extends AbstractPipeline
{
    /**
     * @throws \Throwable
     */
    public function store(array $data): Model
    {
        try {
            DB::beginTransaction();

            $data = $this->pipeline
                ->send($data)
                ->through([
                    ProductStorePipe::class,
                    ProductsWeavesStoreUpdateRelationshipsPipe::class,
                    ProductsProductCategoryStoreUpdateRelationshipsPipe::class,
                    ProductSizesStoreUpdateRelationshipsPipe::class,
                    ProductsSizeCategoriesStoreUpdateRelationshipsPipe::class,
                    ProductsBlogPostsStoreUpdateRelationshipsPipe::class
                ])
                ->thenReturn();

            DB::commit();

            return data_get($data, 'model');
        } catch (\Exception | \Throwable $e) {
            DB::rollBack();
            Log::error($e);

            throw ($e);
        }
    }

    /**
     * @throws \Throwable
     */
    public function update(array $data): void
    {
        try {
            DB::beginTransaction();

            $this->pipeline
                ->send($data)
                ->through([
                    ProductUpdatePipe::class,
                    ProductsWeavesStoreUpdateRelationshipsPipe::class,
                    ProductsProductCategoryStoreUpdateRelationshipsPipe::class,
                    ProductSizesStoreUpdateRelationshipsPipe::class,
                    ProductsSizeCategoriesStoreUpdateRelationshipsPipe::class,
                    ProductsBlogPostsStoreUpdateRelationshipsPipe::class
                ])
                ->thenReturn();

            \DB::commit();
        } catch (\Exception | \Throwable $e) {
            \DB::rollBack();
            \Log::error($e);

            throw ($e);
        }
    }

    /**
     * @throws \Throwable
     */
    public function destroy(int $id): void
    {
        try {
            DB::beginTransaction();

            $this->pipeline
                ->send($id)
                ->through([
                    ProductDestroyPipe::class
                ])
                ->thenReturn();

            \DB::commit();
        } catch (\Exception | \Throwable $e) {
            \DB::rollBack();
            \Log::error($e);

            throw ($e);
        }
    }
}
