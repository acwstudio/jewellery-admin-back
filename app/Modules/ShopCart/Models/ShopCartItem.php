<?php

declare(strict_types=1);

namespace App\Modules\ShopCart\Models;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductOffer;
use Database\Factories\Modules\ShopCart\ShopCartItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $shop_cart_id
 * @property int $product_id
 * @property int $product_offer_id
 * @property int $count
 * @property bool $selected
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property Product $product
 * @property ProductOffer $productOffer
 *
 * @method static self|null find(int $id)
 * @method static self findOrFail(int $id)
 */
class ShopCartItem extends Model
{
    use HasFactory;

    protected $table = 'shop_cart.shop_cart_items';

    protected $fillable = [
        'count',
        'selected'
    ];

    public function shopCart(): BelongsTo
    {
        return $this->belongsTo(ShopCart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function productOffer(): BelongsTo
    {
        return $this->belongsTo(ProductOffer::class);
    }

    protected static function newFactory()
    {
        return app(ShopCartItemFactory::class);
    }
}
