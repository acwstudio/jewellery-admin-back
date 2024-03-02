<?php

namespace Domain\Catalog\Models;

use Domain\Shared\Models\BaseModel;
use Domain\Shared\Observers\RedisCache\RedisCacheable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Price extends BaseModel
{
    use RedisCacheable;

    public const TYPE_RESOURCE = 'prices';

    protected $fillable = ['price_category_id','product_id','value','is_active'];

    public function priceCategory(): BelongsTo
    {
        return $this->belongsTo(PriceCategory::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
