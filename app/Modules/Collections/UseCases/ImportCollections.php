<?php

declare(strict_types=1);

namespace App\Modules\Collections\UseCases;

use App\Modules\Collections\Enums\CollectionImageUrlTypeEnum;
use App\Modules\Collections\Models\Collection as CollectionModel;
use App\Modules\Collections\Models\CollectionImageUrl;
use App\Modules\Collections\Services\CollectionImageUrlService;
use App\Modules\Collections\Services\CollectionService;
use App\Modules\Collections\Services\Import\ImportCollectionService;
use App\Modules\Collections\Support\Filters\CollectionFilter;
use App\Packages\DataObjects\Catalog\Filter\FilterProductData;
use App\Packages\DataObjects\Catalog\Product\ProductGetListData;
use App\Packages\DataObjects\Catalog\Product\ProductItemListData;
use App\Packages\DataObjects\Collections\Collection\CreateCollectionData;
use App\Packages\DataObjects\Collections\Collection\UpdateCollectionData;
use App\Packages\DataObjects\Collections\CollectionImageUrl\CreateCollectionImageUrlData;
use App\Packages\DataObjects\Collections\CollectionImageUrl\UpdateCollectionImageUrlData;
use App\Packages\DataObjects\Collections\Import\ImportCollectionData;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\Events\Sync\CollectionsImported;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use Illuminate\Support\Collection;
use Psr\Log\LoggerInterface;

class ImportCollections
{
    public function __construct(
        private readonly CatalogModuleClientInterface $catalogModuleClient,
        private readonly ImportCollectionService $importCollectionService,
        private readonly CollectionService $collectionService,
        private readonly CollectionImageUrlService $collectionImageUrlService,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(?callable $onEach = null): void
    {
        $this->importCollectionService->import(function (ImportCollectionData $data) use ($onEach) {
            try {
                $this->upsertCollection($data);
            } catch (\Throwable $e) {
                $this->logger->error(
                    "Collection with extID: $data->external_id import error",
                    ['exception' => $e]
                );
            }

            if (null !== $onEach) {
                call_user_func($onEach);
            }
        });
    }

    private function upsertCollection(ImportCollectionData $data): void
    {
        $collection = $this->getCollectionModel($data->external_id, $data->name);
        $products = $this->getCatalogProducts($data);

        if (null !== $collection) {
            if (config('collections.import.collections.update.only_id')) {
                $collection->update(['external_id' => $data->external_id]);
                $this->logger->info('Collection update only external_id', [
                    'collection_id' => $collection->id,
                    'external_id' => $data->external_id
                ]);
                return;
            }

            $collection = $this->collectionService->updateCollection(
                collection: $collection,
                data: $this->getUpdateCollectionData($collection, $data, $products),
                products: $products->pluck('id')->all()
            );
        } else {
            $collection = $this->collectionService->createCollection(
                data: $this->getCreateCollectionData($data, $products),
                products: $products->pluck('id')->all()
            );
        }

        $this->upsertImageUrls($collection, $data);

        CollectionsImported::dispatch($collection->id);
    }

    private function getCollectionModel(string $externalId, string $name): ?CollectionModel
    {
        $collection = $this->collectionService->getByFilter(
            new CollectionFilter(external_id: $externalId)
        )->first();

        if (null === $collection) {
            $collection = $this->collectionService->getByFilter(
                new CollectionFilter(name: $name)
            )->first();
        }

        return $collection;
    }

    private function getCatalogProducts(ImportCollectionData $data): Collection
    {
        if ($data->products->isEmpty()) {
            return collect();
        }

        $filterProductData = new FilterProductData(
            sku: $data->products->implode(',')
        );

        $productCollection = new Collection();
        $isRepeat = true;
        $page = 1;

        while ($isRepeat) {
            $data = new ProductGetListData(
                pagination: new PaginationData($page, 100),
                filter: $filterProductData,
            );
            /** @var ProductItemListData $productItemListData */
            $productItemListData = $this->catalogModuleClient->getScoutProducts($data);
            $productCollection = $productCollection->merge($productItemListData->items->all());

            $isRepeat = $productItemListData->pagination->last_page > $productItemListData->pagination->page;
            $page++;
        }

        return $productCollection;
    }

    private function upsertImageUrls(CollectionModel $collection, ImportCollectionData $data): void
    {
        $images = [
            CollectionImageUrlTypeEnum::PREVIEW->value => $data->preview_image,
            CollectionImageUrlTypeEnum::PREVIEW_MOB->value => $data->preview_image_mob,
            CollectionImageUrlTypeEnum::BANNER->value => $data->banner_image,
            CollectionImageUrlTypeEnum::BANNER_MOB->value => $data->banner_image_mob,
            CollectionImageUrlTypeEnum::EXTENDED_PREVIEW->value => $data->extended_image
        ];

        foreach ($images as $type => $image) {
            $type = CollectionImageUrlTypeEnum::tryFrom($type);
            $this->updateOrCreateImageUrl($collection, $type, $image);
        }
    }

    private function getCreateCollectionData(
        ImportCollectionData $data,
        Collection $catalogProducts
    ): CreateCollectionData {
        return new CreateCollectionData(
            slug: $data->slug,
            name: $data->name,
            description: $data->description,
            is_active: $data->is_active,
            is_hidden: $data->is_hidden,
            products: $catalogProducts->pluck('id')->all(),
            external_id: $data->external_id
        );
    }

    private function getUpdateCollectionData(
        CollectionModel $collection,
        ImportCollectionData $data,
        Collection $catalogProducts
    ): UpdateCollectionData {
        return new UpdateCollectionData(
            id: $collection->id,
            slug: $data->slug,
            name: $data->name,
            description: $data->description,
            is_active: $data->is_active,
            is_hidden: $data->is_hidden,
            products: $catalogProducts->pluck('id')->all(),
            external_id: $data->external_id
        );
    }

    private function updateOrCreateImageUrl(
        CollectionModel $collection,
        CollectionImageUrlTypeEnum $type,
        ?string $path = null,
    ): void {
        if (null === $path) {
            return;
        }

        /** @var CollectionImageUrl|null $imageUrl */
        $imageUrl = $collection->imageUrls->where('type', '=', $type)->first();

        if (null === $imageUrl) {
            $imageUrl = $this->collectionImageUrlService->create(
                new CreateCollectionImageUrlData(
                    collection_id: $collection->id,
                    path: $path,
                    type: $type
                ),
                $collection
            );
        }

        if ($imageUrl->path !== $path) {
            $this->collectionImageUrlService->update(
                $imageUrl,
                new UpdateCollectionImageUrlData(
                    id: $imageUrl->id,
                    collection_id: $collection->id,
                    path: $path,
                    type: $type
                )
            );
        }
    }
}
