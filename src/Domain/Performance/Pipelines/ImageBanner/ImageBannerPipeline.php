<?php

declare(strict_types=1);

namespace Domain\Performance\Pipelines\ImageBanner;

use Domain\AbstractPipeline;
use Domain\Performance\Pipelines\ImageBanner\Pipes\ImageBannerDestroyPipe;
use Domain\Performance\Pipelines\ImageBanner\Pipes\ImageBannersBannersUpdateRelationshipsPipe;
use Domain\Performance\Pipelines\ImageBanner\Pipes\ImageBannerStorePipe;
use Domain\Performance\Pipelines\ImageBanner\Pipes\ImageBannersTypeDeviceUpdateRelationshipsPipe;
use Domain\Performance\Pipelines\ImageBanner\Pipes\ImageBannerUpdatePipe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class ImageBannerPipeline extends AbstractPipeline
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
                    ImageBannerStorePipe::class,
                    ImageBannersBannersUpdateRelationshipsPipe::class,
                    ImageBannersTypeDeviceUpdateRelationshipsPipe::class
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
                    ImageBannerUpdatePipe::class,
                    ImageBannersBannersUpdateRelationshipsPipe::class,
                    ImageBannersTypeDeviceUpdateRelationshipsPipe::class
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
                    ImageBannerDestroyPipe::class
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
