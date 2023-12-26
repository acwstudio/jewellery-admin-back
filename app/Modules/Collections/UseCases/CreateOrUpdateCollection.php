<?php

declare(strict_types=1);

namespace App\Modules\Collections\UseCases;

use App\Modules\Collections\Services\CollectionService;
use App\Packages\DataObjects\Catalog\Filter\FilterProductData;
use App\Packages\DataObjects\Catalog\Product\ProductGetListData;
use App\Packages\DataObjects\Catalog\Product\ProductListData;
use App\Packages\DataObjects\Collections\Collection\CollectionData;
use App\Packages\DataObjects\Collections\Collection\CreateCollectionData;
use App\Packages\DataObjects\Collections\Collection\UpdateCollectionData;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\ModuleClients\CatalogModuleClientInterface;

class CreateOrUpdateCollection
{
    public function __construct(
        private readonly CatalogModuleClientInterface $catalogModuleClient,
        private readonly CollectionService $collectionService
    ) {
    }

    public function __invoke(CreateCollectionData|UpdateCollectionData $data): CollectionData
    {
        if ($data instanceof CreateCollectionData) {
            return $this->create($data);
        }

        return $this->update($data);
    }

    private function create(CreateCollectionData $data): CollectionData
    {
        $productListData = null;
        if (!empty($data->products)) {
            $productListData = $this->getProductListData($data->products);
        }

        $collection = $this->collectionService->createCollection(
            $data,
            $data->preview_image_id,
            $data->preview_image_mob_id,
            $data->banner_image_id,
            $data->banner_image_mob_id,
            $data->extended_image_id,
            $data->stones,
            $productListData?->items->toCollection()->pluck('id')->toArray() ?? [],
            $data->images
        );

        return CollectionData::fromModel($collection);
    }

    private function update(UpdateCollectionData $data): CollectionData
    {
        $productListData = null;
        if (!empty($data->products)) {
            $productListData = $this->getProductListData($data->products);
        }

        $collection = $this->collectionService->updateCollection(
            $data->id,
            $data,
            $data->preview_image_id,
            $data->preview_image_mob_id,
            $data->banner_image_id,
            $data->banner_image_mob_id,
            $data->extended_image_id,
            $data->stones,
            $productListData?->items->toCollection()->pluck('id')->toArray() ?? [],
            $data->images
        );

        return CollectionData::fromModel($collection);
    }

    private function getProductListData(array $productIds): ProductListData
    {
        $data = new ProductGetListData(
            pagination: new PaginationData(1, count($productIds)),
            filter: new FilterProductData(ids: $productIds)
        );

        return $this->catalogModuleClient->getProducts($data);
    }
}
