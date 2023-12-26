<?php

declare(strict_types=1);

namespace App\Packages\ModuleClients;

use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\DataObjects\Promotions\Filter\FilterPromocodeUsageData;
use App\Packages\DataObjects\Promotions\Promocode\Price\GetPromocodePriceListData;
use App\Packages\DataObjects\Promotions\Promocode\PromocodeData;
use App\Packages\DataObjects\Promotions\Promocode\PromocodeExtendedData;
use App\Packages\DataObjects\Promotions\Promocode\SetPromocodeUsageOrderId;
use App\Packages\DataObjects\Promotions\Promotion\PromotionData;
use App\Packages\DataObjects\Promotions\Sales\CatalogProduct\CatalogProductListData;
use App\Packages\DataObjects\Promotions\Sales\CatalogProduct\GetCatalogProductListData;
use App\Packages\DataObjects\Promotions\Sales\Sale\SaleData;
use App\Packages\DataObjects\Promotions\Sales\Sale\SaleListData;
use App\Packages\DataObjects\Promotions\Sales\SaleProduct\GetSaleProductListData;
use App\Packages\DataObjects\Promotions\Sales\SaleProduct\SaleProductListData;
use Illuminate\Support\Collection;

interface PromotionsModuleClientInterface
{
    public function getActivePromocode(string $shopCartToken): ?PromocodeData;
    public function applyPromocode(string $promocode);
    public function cancelPromocode(): void;
    public function importPromotions();
    public function getPromotion(int $id): PromotionData;
    public function getPromocodePrices(GetPromocodePriceListData $data): Collection;
    public function getSale(string $slug): SaleData;
    public function getSales(?PaginationData $data = null): SaleListData;
    public function getCatalogProducts(GetCatalogProductListData $data): CatalogProductListData;
    public function getSaleProducts(GetSaleProductListData $data): SaleProductListData;
    public function importSale(int $promotionId): void;
    public function getPromocodeByPromotionExternalId(string $promotionExternalId): ?PromocodeData;
    public function getActivePromocodeExtended(string $shopCartToken): ?PromocodeExtendedData;
    public function getPromocodeUsages(FilterPromocodeUsageData $data): Collection;
    public function setPromocodeUsageOrderId(SetPromocodeUsageOrderId $data): void;
}
