<?php

declare(strict_types=1);

namespace Http\Controllers\Promotions\Promocodes;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Promotions\Enums\PromotionBenefitTypeEnum;
use App\Modules\Promotions\Models\Promotion;
use App\Modules\Promotions\Models\PromotionBenefit;
use App\Modules\Promotions\Models\PromotionCondition;
use App\Modules\Promotions\Modules\Promocodes\Models\PromocodeUsage;
use App\Modules\ShopCart\Models\ShopCart;
use App\Modules\ShopCart\Models\ShopCartItem;
use App\Modules\Users\Models\User;
use App\Packages\Enums\Users\RoleEnum;
use App\Packages\Exceptions\Promotions\ApplyPromocodeException;
use App\Packages\Exceptions\Promotions\PromocodeNotFoundException;
use App\Packages\ModuleClients\PromotionsModuleClientInterface;
use Illuminate\Support\Collection;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PromocodeControllerCancelTest extends TestCase
{
    private const METHOD = '/api/v1/promotions/promocode/cancel';
    private User $user;
    private PromotionsModuleClientInterface $promotionsModuleClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->getUser(RoleEnum::USER);
        Sanctum::actingAs($this->user);
        $this->promotionsModuleClient = app(PromotionsModuleClientInterface::class);
    }

    /**
     * @throws ApplyPromocodeException
     * @throws PromocodeNotFoundException
     */
    public function testSuccessful(): void
    {
        $this->setPromocode();
        $response = $this->post(self::METHOD);

        $response->assertSuccessful();
    }

    private function setPromocode(): void
    {
        $this->createPromocode();
        $this->createShopCartWithProducts();
    }

    private function createPromocode(): void
    {
        $promotion = Promotion::factory()->create(['is_active' => true, 'description' => 'Промокод 10%']);

        PromotionCondition::factory()->create(['promotion_id' => $promotion]);

        /** @var Collection<int, PromotionBenefit> $promotionBenefit */
        $promotionBenefit = PromotionBenefit::factory()->create([
            'promotion_id' => $promotion,
            'type' => PromotionBenefitTypeEnum::PROMOCODE,
            'promocode' => 'promocode'
        ]);

        $this->promotionsModuleClient->applyPromocode($promotionBenefit->first()->promocode);
    }

    private function createShopCartWithProducts(): void
    {
        $products = Product::factory(3)->create(['setFull' => true]);

        /** @var ShopCart|null $shopCart */
        $shopCart = ShopCart::query()->where('user_id', $this->user->user_id)->first();
        if (!$shopCart) {
            $shopCart = ShopCart::factory()->create(['user_id' => $this->user->user_id]);
        }

        /** @var Product $product */
        foreach ($products as $product) {
            /** @var ProductOffer $offer */
            $offer = $product->productOffers->first();
            ShopCartItem::factory()->create([
                'shop_cart_id' => $shopCart->getKey(),
                'product_id' => $product->getKey(),
                'product_offer_id' => $offer->getKey(),
                'count' => 1
            ]);
        }
    }
}
