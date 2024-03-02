<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\Price;

use Domain\AbstractPipeline;
use Domain\Catalog\Pipelines\Price\Pipes\PriceDestroyPipe;
use Domain\Catalog\Pipelines\Price\Pipes\PricesPriceCategoryStoreUpdateRelationshipsPipe;
use Domain\Catalog\Pipelines\Price\Pipes\PricesProductStoreUpdateRelationshipsPipe;
use Domain\Catalog\Pipelines\Price\Pipes\PriceStorePipe;
use Domain\Catalog\Pipelines\Price\Pipes\PriceUpdatePipe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class PricePipeline extends AbstractPipeline
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
                    PriceStorePipe::class,
                    PricesProductStoreUpdateRelationshipsPipe::class,
                    PricesPriceCategoryStoreUpdateRelationshipsPipe::class,
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
                    PriceUpdatePipe::class,
                    PricesProductStoreUpdateRelationshipsPipe::class,
                    PricesPriceCategoryStoreUpdateRelationshipsPipe::class,
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
                    PriceDestroyPipe::class
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
