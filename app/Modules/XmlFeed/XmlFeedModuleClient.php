<?php

declare(strict_types=1);

namespace App\Modules\XmlFeed;

use App\Modules\XmlFeed\Services\MindboxService;
use App\Modules\XmlFeed\Services\VKService;
use App\Modules\XmlFeed\Services\YandexService;
use App\Packages\DataObjects\Catalog\Filter\FilterProductData;
use App\Packages\DataObjects\Catalog\Filter\MinMaxData;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Modules\XmlFeed\Enums\FeedTypeEnum;
use App\Modules\XmlFeed\Services\AvitoService;
use App\Modules\XmlFeed\UseCases\GetProducts;
use App\Packages\DataObjects\Catalog\Product\ProductGetListData;
use App\Packages\ModuleClients\XmlFeedModuleClientInterface;
use DOMDocument;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

final class XmlFeedModuleClient implements XmlFeedModuleClientInterface
{
    public function __construct(
        private readonly AvitoService $avitoService,
        private readonly YandexService $yandexService,
        private readonly MindboxService $mindboxService,
        private readonly VKService $vkService
    ) {
    }

    public function generate(FeedTypeEnum $type, ?callable $onEach = null): void
    {
        $document = match ($type) {
            FeedTypeEnum::AVITO => $this->getDocumentAvito($onEach),
            FeedTypeEnum::YANDEX => $this->getDocumentYandex($onEach),
            FeedTypeEnum::VK => $this->getDocumentVK($onEach),
            FeedTypeEnum::MINDBOX => $this->getDocumentMindbox($onEach)
        };

        if ($document instanceof DOMDocument) {
            $this->save($type, $document);
        }
    }

    private function getProducts(?FilterProductData $filterProductData = null, ?int $limit = null): Collection
    {
        $productCollection = new Collection();
        $isRepeat = true;
        $page = 1;

        while ($isRepeat) {
            $data = new ProductGetListData(
                pagination: new PaginationData($page, 100),
                filter: $filterProductData,
                is_full: true
            );
            /** @var \App\Packages\DataObjects\Catalog\Product\ProductListData $productListData */
            $productListData = App::call(GetProducts::class, ['data' => $data]);
            $productCollection = $productCollection->merge($productListData->items->all());
            $isRepeat = $productListData->pagination->last_page > $productListData->pagination->page;

            if (!empty($limit) && $isRepeat && $productCollection->count() >= $limit) {
                $isRepeat = false;
            }
            $page++;
        }

        return $productCollection;
    }

    private function save(FeedTypeEnum $type, DOMDocument $document): void
    {
        $folder = config('xml_feed.folder');
        $content = $document->saveXML();
        Storage::put($folder . '/' . $type->getPath(), $content);
    }

    private function getDocumentAvito(?callable $onEach = null): DOMDocument
    {
        $products = $this->getProducts(
            new FilterProductData(
                price: new MinMaxData(min: 5000, max: 60000),
                qty_in_stock: 10,
                has_image: true,
                is_active: true,
                exclude_sku: 'Л',
                ignore_common: true,
            ),
            9000
        );
        return $this->avitoService->getDocument($products, $onEach);
    }

    private function getDocumentYandex(?callable $onEach = null): DOMDocument
    {
        $products = $this->getProducts(
            new FilterProductData(
                price: new MinMaxData(min: 1000, max: 9999999),
                in_stock: true,
                has_image: true,
                is_active: true,
                ignore_common: true,
            )
        );
        return $this->yandexService->getDocument($products, $onEach);
    }

    /** Делается на основе Yandex, отличие лишь в выборке (товары с дропшипингом) */
    private function getDocumentVK(?callable $onEach = null): DOMDocument
    {
        $products = $this->getProducts(
            new FilterProductData(
                price: new MinMaxData(min: 5000, max: 9999999),
                in_stock: true,
                has_image: true,
                is_active: true,
                ignore_common: true,
            )
        );
        return $this->vkService->getDocument($products, $onEach);
    }

    private function getDocumentMindbox(?callable $onEach = null): DOMDocument
    {
        $products = $this->getProducts(
            new FilterProductData(
                price: new MinMaxData(min: 1, max: 9999999),
                ignore_common: true
            )
        );
        return $this->mindboxService->getDocument($products, $onEach);
    }
}
