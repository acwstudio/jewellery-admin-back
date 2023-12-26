<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Users\Services;

use App\Modules\Catalog\Models\Product;
use App\Modules\Users\Models\WishlistProduct;
use App\Modules\Users\Services\WishlistProductService;
use App\Packages\Events\WishlistProductChanged;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class WishlistServiceTest extends TestCase
{
    private WishlistProductService $wishlistService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->wishlistService = App::make(WishlistProductService::class);
    }

    public function testSuccessfulGetWishlist(): void
    {
        $user = $this->getUser();
        WishlistProduct::factory()->create(['user_id' => $user]);

        $wishlistProductCollection = $this->wishlistService->getWishlistProducts($user);
        Event::fake([WishlistProductChanged::class])->dispatch($wishlistProductCollection);
        self::assertCount(1, $wishlistProductCollection->toArray());
    }

    public function testSuccessfulCreateWishlistProduct(): void
    {
        $user = $this->getUser();
        $product = Product::factory()->create(['setFull' => true]);

        $wishlist = $this->wishlistService->createWishlistProduct($product->getKey(), $user);

        $wishlistProductCollection = $this->wishlistService->getWishlistProducts($user);
        Event::fake([WishlistProductChanged::class])->dispatch($wishlistProductCollection);

        self::assertInstanceOf(WishlistProduct::class, $wishlist);
    }

    public function testSuccessfulDeleteWishlistProduct(): void
    {
        $user = $this->getUser();
        /** @var WishlistProduct $wishlist */
        $wishlist = WishlistProduct::factory()->create(['user_id' => $user]);

        $this->wishlistService->deleteWishlistProduct($wishlist->product_id, $user);

        $wishlistProductCollection = $this->wishlistService->getWishlistProducts($user);
        Event::fake([WishlistProductChanged::class])->dispatch($wishlistProductCollection);

        $this->assertModelMissing($wishlist);
    }
}
