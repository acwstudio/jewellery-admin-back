<?php

namespace Domain\Catalog\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Domain\Blog\Models\BlogPost;
use Domain\Shared\Models\BaseModel;
use Domain\Shared\Observers\RedisCache\RedisCacheable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends BaseModel
{
    use RedisCacheable, Sluggable;

    public const TYPE_RESOURCE = 'products';

    protected $fillable = [
        'product_category_id',
        'brand_id',
        'sku',
        'name',
        'slug',
        'summary',
        'description',
        'is_active',
        'weight',
        'core_id'
    ];

    public function blogPosts(): BelongsToMany
    {
        return $this->belongsToMany(BlogPost::class);
    }

    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function weaves(): BelongsToMany
    {
        return$this->belongsToMany(Weave::class);
    }

    public function sizeCategories(): BelongsToMany
    {
        return $this->belongsToMany(SizeCategory::class, 'sizes');
    }

    public function sizes(): HasMany
    {
        return $this->hasMany(Size::class);
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
