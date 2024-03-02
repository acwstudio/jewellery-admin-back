<?php

namespace Domain\Catalog\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Domain\Shared\Models\BaseModel;
use Domain\Shared\Observers\RedisCache\RedisCacheable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PriceCategory extends BaseModel
{
    use RedisCacheable, Sluggable;

    public const TYPE_RESOURCE = 'priceCategories';

    protected $fillable = ['name','slug','is_active'];

    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'prices');
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
