<?php

declare(strict_types=1);

namespace App\Modules\Collections\UseCases;

use App\Modules\Collections\Models\Collection;
use App\Modules\Collections\Services\CollectionProductImportService;
use App\Modules\Collections\Services\CollectionService;
use App\Packages\DataObjects\Collections\CollectionProduct\ImportCollectionProductData;
use Illuminate\Support\Facades\DB;
use Psr\Log\LoggerInterface;

class ImportCollectionProducts
{
    public function __construct(
        private readonly CollectionProductImportService $collectionProductImportService,
        private readonly CollectionService $collectionService,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(?callable $onEach = null): void
    {
        try {
            $dataList = $this->collectionProductImportService->import();
        } catch (\Throwable $e) {
            $this->logger->error(
                "Get collection products from Monolith error",
                ['exception' => $e]
            );
            return;
        }

        /** @var ImportCollectionProductData $data */
        foreach ($dataList as $data) {
            try {
                DB::transaction(function () use ($data) {
                    $this->updateCollection($data);
                });
            } catch (\Throwable $e) {
                $this->logger->error(
                    "Collection products with collectionId: $data->collection_id import error",
                    ['exception' => $e]
                );
            }

            if (null !== $onEach) {
                call_user_func($onEach);
            }
        }
    }

    private function updateCollection(ImportCollectionProductData $data): void
    {
        if (empty($data->collection_id)) {
            return;
        }

        $collection = $this->collectionService->getCollection($data->collection_id);
        if (!$collection instanceof Collection) {
            return;
        }

        $collection->products()->sync($data->product_ids);
        $collection->categories()->sync(array_unique($data->category_ids));
    }
}
