<?php

namespace Domain\Catalog\Models;

use Domain\Shared\Models\BaseModel;
use Domain\Shared\Observers\RedisCache\RedisCacheable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Size extends BaseModel
{
    use RedisCacheable;

    public const TYPE_RESOURCE = 'sizes';

    protected $fillable = ['product_id','size_category_id','value','is_active'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }

    public function sizeCategory(): BelongsTo
    {
        return $this->belongsTo(SizeCategory::class);
    }
}
