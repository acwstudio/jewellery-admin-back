<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\SizeCategory;

use Domain\AbstractPipeline;
use Domain\Catalog\Pipelines\SizeCategory\Pipes\SizeCategoriesProductsStoreUpdateRelationshipsPipe;
use Domain\Catalog\Pipelines\SizeCategory\Pipes\SizeCategoryDestroyPipe;
use Domain\Catalog\Pipelines\SizeCategory\Pipes\SizeCategorySizesStoreUpdateRelationshipsPipe;
use Domain\Catalog\Pipelines\SizeCategory\Pipes\SizeCategoryStorePipe;
use Domain\Catalog\Pipelines\SizeCategory\Pipes\SizeCategoryUpdatePipe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class SizeCategoryPipeline extends AbstractPipeline
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
                    SizeCategoryStorePipe::class,
                    SizeCategoriesProductsStoreUpdateRelationshipsPipe::class,
                    SizeCategorySizesStoreUpdateRelationshipsPipe::class,
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
                    SizeCategoryUpdatePipe::class,
                    SizeCategoriesProductsStoreUpdateRelationshipsPipe::class,
                    SizeCategorySizesStoreUpdateRelationshipsPipe::class,
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
                    SizeCategoryDestroyPipe::class
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
