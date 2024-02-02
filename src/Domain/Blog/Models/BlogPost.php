<?php

declare(strict_types=1);

namespace Domain\Blog\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Database\Factories\Blog\BlogPostFactory;
use Domain\Catalog\Models\Product;
use Domain\Shared\Models\BaseModel;
use Domain\Shared\Observers\RedisCache\RedisCacheable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BlogPost extends BaseModel
{
    use Sluggable, RedisCacheable;

    public const TYPE_RESOURCE = 'blogPosts';

    protected $fillable = [
        'blog_category_id',
        'slug',
        'title',
        'description',
        'image_id',
        'preview_id',
        'content',
        'status',
        'published_at',
        'is_main',
        'active'
    ];

    protected $casts = [
        'published_at' => 'date'
    ];

    public function blogCategory(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
