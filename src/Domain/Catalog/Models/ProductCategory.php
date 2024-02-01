<?php

namespace Domain\Catalog\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Domain\Shared\Models\BaseModel;
use Domain\Shared\Observers\RedisCache\RedisCacheable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends BaseModel
{
    use Sluggable, RedisCacheable;

    public const TYPE_RESOURCE = 'productCategories';

    protected $fillable = ['parent_id','name','description','slug','is_active'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
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
