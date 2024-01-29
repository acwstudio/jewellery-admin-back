<?php

declare(strict_types=1);

namespace Domain\Blog\Pipelines\BlogCategory;

use Domain\AbstractPipeline;
use Domain\Blog\Pipelines\BlogCategory\Pipes\BlogCategoriesParentUpdateRelationshipsPipe;
use Domain\Blog\Pipelines\BlogCategory\Pipes\BlogCategoryBlogPostsUpdateRelationshipsPipe;
use Domain\Blog\Pipelines\BlogCategory\Pipes\BlogCategoryChildrenUpdateRelationshipsPipe;
use Domain\Blog\Pipelines\BlogCategory\Pipes\BlogCategoryDestroyPipe;
use Domain\Blog\Pipelines\BlogCategory\Pipes\BlogCategoryStorePipe;
use Domain\Blog\Pipelines\BlogCategory\Pipes\BlogCategoryUpdatePipe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class BlogCategoryPipeline extends AbstractPipeline
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
                    BlogCategoryStorePipe::class,
                    BlogCategoryBlogPostsUpdateRelationshipsPipe::class,
                    BlogCategoriesParentUpdateRelationshipsPipe::class,
                    BlogCategoryChildrenUpdateRelationshipsPipe::class,
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
                    BlogCategoryUpdatePipe::class,
                    BlogCategoryBlogPostsUpdateRelationshipsPipe::class,
                    BlogCategoriesParentUpdateRelationshipsPipe::class,
                    BlogCategoryChildrenUpdateRelationshipsPipe::class,
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
                    BlogCategoryDestroyPipe::class
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
