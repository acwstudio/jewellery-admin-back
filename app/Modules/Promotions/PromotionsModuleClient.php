<?php

declare(strict_types=1);

namespace App\Modules\Promotions;

use App\Modules\Promotions\Modules\Promocodes\Services\PromocodeBenefitService;
use App\Modules\Promotions\Modules\Promocodes\Support\Filters\PromocodePriceFilter;
use App\Modules\Promotions\Services\PromotionService;
use App\Modules\Promotions\UseCases\ApplyPromocode;
use App\Modules\Promotions\UseCases\GetPromocodePrice;
use App\Modules\Promotions\UseCases\GetPromocodeUsage;
use App\Modules\Promotions\UseCases\GetSaleProductUseCase;
use App\Modules\Promotions\UseCases\GetSaleUseCase;
use App\Modules\Promotions\UseCases\ImportPromotions;
use App\Modules\Promotions\UseCases\ImportSale;
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
use App\Packages\ModuleClients\PromotionsModuleClientInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Throwable;

class PromotionsModuleClient implements PromotionsModuleClientInterface
{
    public function __construct(
        private readonly PromotionService $promotionService,
        private readonly PromocodeBenefitService $promocodeBenefitService
    ) {
    }

    public function importPromotions(): void
    {
        App::call(ImportPromotions::class);
    }

    public function applyPromocode(string $promocode): void
    {
        App::call(ApplyPromocode::class, [
            'promocode' => $promocode,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function cancelPromocode(): void
    {
        $this->promocodeBenefitService->cancel();
    }

    public function getPromotion(int $id): PromotionData
    {
        $promotion = $this->promotionService->getById($id);
        return PromotionData::fromModel($promotion);
    }

    public function getPromocodePrices(GetPromocodePriceListData $data): Collection
    {
        /** @var GetPromocodePrice $useCase */
        $useCase = App::make(GetPromocodePrice::class);
        return $useCase->getCollectionByFilter(
            new PromocodePriceFilter(
                $data->filter?->shop_cart_token,
                $data->filter?->product_offer_id
            )
        );
    }

    public function getSale(string $slug): SaleData
    {
        /** @var GetSaleUseCase $useCase */
        $useCase = App::make(GetSaleUseCase::class);
        return $useCase->getBySlug($slug);
    }

    public function getSales(?PaginationData $data = null): SaleListData
    {
        /** @var GetSaleUseCase $useCase */
        $useCase = App::make(GetSaleUseCase::class);
        return $useCase->getList($data);
    }

    public function getCatalogProducts(GetCatalogProductListData $data): CatalogProductListData
    {
        /** @var GetSaleProductUseCase $useCase */
        $useCase = App::make(GetSaleProductUseCase::class);
        return $useCase->getListByCatalog($data);
    }

    public function getSaleProducts(GetSaleProductListData $data): SaleProductListData
    {
        /** @var GetSaleProductUseCase $useCase */
        $useCase = App::make(GetSaleProductUseCase::class);
        return $useCase->getList($data);
    }

    public function importSale(int $promotionId): void
    {
        App::call(ImportSale::class, ['promotionId' => $promotionId]);
    }

    public function getActivePromocode(string $shopCartToken): ?PromocodeData
    {
        $promotionBenefit = $this->promocodeBenefitService->getActive($shopCartToken);

        if ($promotionBenefit === null) {
            return null;
        }

        return PromocodeData::fromModel($promotionBenefit);
    }

    public function getPromocodeByPromotionExternalId(string $promotionExternalId): ?PromocodeData
    {
        $promotionBenefit = $this->promocodeBenefitService->getByPromotionExternalId($promotionExternalId);

        if ($promotionBenefit === null) {
            return null;
        }

        return PromocodeData::fromModel($promotionBenefit);
    }

    public function getActivePromocodeExtended(string $shopCartToken): ?PromocodeExtendedData
    {
        $promotionBenefit = $this->promocodeBenefitService->getActive($shopCartToken);

        if ($promotionBenefit === null) {
            return null;
        }

        return PromocodeExtendedData::fromModel($promotionBenefit);
    }

    public function getPromocodeUsages(FilterPromocodeUsageData $data): Collection
    {
        /** @var GetPromocodeUsage $useCase */
        $useCase = App::make(GetPromocodeUsage::class);
        return $useCase->getCollectionByFilter($data);
    }

    public function setPromocodeUsageOrderId(SetPromocodeUsageOrderId $data): void
    {
        /** @var GetPromocodeUsage $useCase */
        $useCase = App::make(GetPromocodeUsage::class);
        $useCase->setOrderId($data);
    }
}
