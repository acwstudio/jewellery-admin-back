<?php

namespace Domain\Catalog\Models;

use Domain\Shared\Models\BaseModel;
use Domain\Shared\Observers\RedisCache\RedisCacheable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Price extends BaseModel
{
    use RedisCacheable;

    public const TYPE_RESOURCE = 'prices';

    protected $fillable = ['price_category_id','product_id','value','is_active'];

    public function priceCategory(): BelongsTo
    {
        return $this->belongsTo(PriceCategory::class);
    }

    public function product(): HasOneThrough
    {
        return $this->hasOneThrough(Product::class, Size::class, 'id', 'id', 'size_id', 'product_id');
    }

    public function sizeCategory(): HasOneThrough
    {
        return $this->hasOneThrough(SizeCategory::class, Size::class, 'id', 'id', 'size_id', 'size_category_id');
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class);
    }
}
