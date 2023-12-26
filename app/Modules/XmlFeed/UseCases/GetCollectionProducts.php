<?php

declare(strict_types=1);

namespace App\Modules\XmlFeed\UseCases;

use App\Packages\DataObjects\Collections\CollectionProduct\CollectionProductListItemListData;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\ModuleClients\CollectionModuleClientInterface;
use Illuminate\Support\Collection;
use Psr\Log\LoggerInterface;

class GetCollectionProducts
{
    public function __construct(
        private readonly CollectionModuleClientInterface $collectionModuleClient,
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(?int $limit = null): Collection
    {
        $responseCollection = new Collection();
        $isRepeat = true;
        $page = 1;

        while ($isRepeat) {
            $responseListData = $this->getCollectionProductListItems(
                new PaginationData($page, 100)
            );
            $responseCollection = $responseCollection->merge($responseListData->items->all());
            $isRepeat = $responseListData->pagination->last_page > $responseListData->pagination->page;

            if (!empty($limit) && $isRepeat && $responseCollection->count() >= $limit) {
                $isRepeat = false;
            }
            $page++;
        }

        return $responseCollection;
    }

    private function getCollectionProductListItems(PaginationData $data): CollectionProductListItemListData
    {
        $response = $this->collectionModuleClient->getCollectionProductListItems($data);
        $this->logger->info('XmlFeed::GetCollectionProducts', ['pagination' => $response->pagination->all()]);
        return $response;
    }
}
