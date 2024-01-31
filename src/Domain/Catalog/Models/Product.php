<?php

namespace Domain\Catalog\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Domain\Blog\Models\BlogPost;
use Domain\Shared\Models\BaseModel;
use Domain\Shared\Observers\RedisCache\RedisCacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends BaseModel
{
    use HasFactory, RedisCacheable, Sluggable;

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
        'weight'
    ];

    public function blogPosts(): BelongsToMany
    {
        return $this->belongsToMany(BlogPost::class);
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
