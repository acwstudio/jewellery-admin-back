<?php

declare(strict_types=1);

namespace Domain\Performance\Pipelines\TypeBanner;

use Domain\AbstractPipeline;
use Domain\Performance\Pipelines\TypeBanner\Pipes\TypeBannerBannersUpdateRelationshipsPipe;
use Domain\Performance\Pipelines\TypeBanner\Pipes\TypeBannerDestroyPipe;
use Domain\Performance\Pipelines\TypeBanner\Pipes\TypeBannerStorePipe;
use Domain\Performance\Pipelines\TypeBanner\Pipes\TypeBannerUpdatePipe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class TypeBannerPipeline extends AbstractPipeline
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
                    TypeBannerStorePipe::class,
                    TypeBannerBannersUpdateRelationshipsPipe::class,
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
                    TypeBannerUpdatePipe::class,
                    TypeBannerBannersUpdateRelationshipsPipe::class,
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
                    TypeBannerDestroyPipe::class
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
