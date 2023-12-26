<?php

declare(strict_types=1);

namespace App\Modules\XmlFeed\UseCases;

use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\DataObjects\Live\LiveProduct\LiveProductListItemListData;
use App\Packages\ModuleClients\LiveModuleClientInterface;
use Illuminate\Support\Collection;
use Psr\Log\LoggerInterface;

class GetLiveProducts
{
    public function __construct(
        private readonly LiveModuleClientInterface $liveModuleClient,
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(?int $limit = null): Collection
    {
        $responseCollection = new Collection();
        $isRepeat = true;
        $page = 1;

        while ($isRepeat) {
            $responseListData = $this->getLiveProductListItems(
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

    private function getLiveProductListItems(PaginationData $data): LiveProductListItemListData
    {
        $response = $this->liveModuleClient->getLiveProductListItems($data);
        $this->logger->info('XmlFeed::GetLiveProducts', ['pagination' => $response->pagination->all()]);
        return $response;
    }
}
