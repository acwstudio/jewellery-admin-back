<?php

declare(strict_types=1);

namespace App\Modules\Collections\UseCases;

use App\Modules\Collections\Models\Collection as CollectionModel;
use App\Modules\Collections\Services\CollectionService;
use App\Modules\Collections\Support\Filters\CollectionFilter;
use App\Packages\DataObjects\Catalog\Filter\FilterProductData;
use App\Packages\DataObjects\Catalog\Product\ProductGetListData;
use App\Packages\DataObjects\Catalog\Product\ProductListData;
use App\Packages\DataObjects\Collections\Export\ExportCollectionData;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\ModuleClients\AMQPModuleClientInterface;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use Illuminate\Support\Collection;
use Psr\Log\LoggerInterface;

class ExportCollections
{
    public function __construct(
        private readonly CatalogModuleClientInterface $catalogModuleClient,
        private readonly AMQPModuleClientInterface $AMQPModuleClient,
        private readonly CollectionService $collectionService,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(CollectionFilter $filter): void
    {
        $models = $this->collectionService->getByFilter($filter);
        /** @var CollectionModel $model */
        foreach ($models as $model) {
            $skuList = $this->getProductSkuArray($model);
            $data = new ExportCollectionData(
                id: $model->id,
                name: $model->name,
                sku_list: $skuList
            );

            $this->send($data);

            $this->logger->info('[ExportCollection] Successful send', [
                'id' => $model->id,
                'name' => $model->name,
                'countSku' => count($skuList)
            ]);
        }
    }

    private function getProductSkuArray(CollectionModel $collection): array
    {
        if ($collection->products()->allRelatedIds()->count() === 0) {
            return [];
        }

        $filterProductData = new FilterProductData(
            ids: $collection->products()->allRelatedIds()->all()
        );

        $productCollection = new Collection();
        $isRepeat = true;
        $page = 1;

        while ($isRepeat) {
            $data = new ProductGetListData(
                pagination: new PaginationData($page, 100),
                filter: $filterProductData,
            );
            /** @var ProductListData $productListData */
            $productListData = $this->catalogModuleClient->getProducts($data);
            $productCollection = $productCollection->merge($productListData->items->all());

            $isRepeat = $productListData->pagination->last_page > $productListData->pagination->page;
            $page++;
        }

        return $productCollection->pluck('sku')->all();
    }

    private function send(ExportCollectionData $data): void
    {
        $queue = config('export.queues.collections');
        $this->AMQPModuleClient->publish($queue, $data->toJson());
    }
}
