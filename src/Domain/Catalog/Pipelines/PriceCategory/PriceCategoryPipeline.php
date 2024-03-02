<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\PriceCategory;

use Domain\AbstractPipeline;
use Domain\Catalog\Pipelines\PriceCategory\Pipes\PriceCategoriesProductsStoreUpdateRelationshipsPipe;
use Domain\Catalog\Pipelines\PriceCategory\Pipes\PriceCategoryDestroyPipe;
use Domain\Catalog\Pipelines\PriceCategory\Pipes\PriceCategoryPricesStoreUpdateRelationshipsPipe;
use Domain\Catalog\Pipelines\PriceCategory\Pipes\PriceCategoryStorePipe;
use Domain\Catalog\Pipelines\PriceCategory\Pipes\PriceCategoryUpdatePipe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class PriceCategoryPipeline extends AbstractPipeline
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
                    PriceCategoryStorePipe::class,
                    PriceCategoriesProductsStoreUpdateRelationshipsPipe::class,
                    PriceCategoryPricesStoreUpdateRelationshipsPipe::class,
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
                    PriceCategoryUpdatePipe::class,
                    PriceCategoriesProductsStoreUpdateRelationshipsPipe::class,
                    PriceCategoryPricesStoreUpdateRelationshipsPipe::class,
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
                    PriceCategoryDestroyPipe::class
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
