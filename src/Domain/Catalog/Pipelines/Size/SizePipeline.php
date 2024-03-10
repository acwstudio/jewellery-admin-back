<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\Size;

use Domain\AbstractPipeline;
use Domain\Catalog\Pipelines\Size\Pipes\SizeDestroyPipe;
use Domain\Catalog\Pipelines\Size\Pipes\SizePricesStoreUpdateRelationshipsPipe;
use Domain\Catalog\Pipelines\Size\Pipes\SizesPriceCategoriesStoreUpdateRelationshipsPipe;
use Domain\Catalog\Pipelines\Size\Pipes\SizesProductStoreUpdateRelationshipsPipe;
use Domain\Catalog\Pipelines\Size\Pipes\SizesSizeCategoryStoreUpdateRelationshipsPipe;
use Domain\Catalog\Pipelines\Size\Pipes\SizeStorePipe;
use Domain\Catalog\Pipelines\Size\Pipes\SizeUpdatePipe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class SizePipeline extends AbstractPipeline
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
                    SizeStorePipe::class,
                    SizePricesStoreUpdateRelationshipsPipe::class,
                    SizesPriceCategoriesStoreUpdateRelationshipsPipe::class,
                    SizesProductStoreUpdateRelationshipsPipe::class,
                    SizesSizeCategoryStoreUpdateRelationshipsPipe::class,
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
                    SizeUpdatePipe::class,
                    SizePricesStoreUpdateRelationshipsPipe::class,
                    SizesPriceCategoriesStoreUpdateRelationshipsPipe::class,
                    SizesProductStoreUpdateRelationshipsPipe::class,
                    SizesSizeCategoryStoreUpdateRelationshipsPipe::class,
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
                    SizeDestroyPipe::class
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
