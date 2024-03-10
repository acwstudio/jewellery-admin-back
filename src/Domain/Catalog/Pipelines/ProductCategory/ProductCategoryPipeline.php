<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\ProductCategory;

use Domain\AbstractPipeline;
use Domain\Catalog\Pipelines\ProductCategory\Pipes\ProductCategoryDestroyPipe;
use Domain\Catalog\Pipelines\ProductCategory\Pipes\ProductCategoryProductsStoreUpdateRelationshipsPipe;
use Domain\Catalog\Pipelines\ProductCategory\Pipes\ProductCategoryStorePipe;
use Domain\Catalog\Pipelines\ProductCategory\Pipes\ProductCategoryUpdatePipe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class ProductCategoryPipeline extends AbstractPipeline
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
                    ProductCategoryStorePipe::class,
                    ProductCategoryProductsStoreUpdateRelationshipsPipe::class
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
                    ProductCategoryUpdatePipe::class,
                    ProductCategoryProductsStoreUpdateRelationshipsPipe::class,
                ])
                ->thenReturn();

            DB::commit();
        } catch (\Exception | \Throwable $e) {
            DB::rollBack();
            Log::error($e);

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
                    ProductCategoryDestroyPipe::class
                ])
                ->thenReturn();

            DB::commit();
        } catch (\Exception | \Throwable $e) {
            DB::rollBack();
            Log::error($e);

            throw ($e);
        }
    }
}
