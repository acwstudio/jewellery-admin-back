<?php

declare(strict_types=1);

namespace Domain\Blog\Models;

use Cviebrock\EloquentSluggable\Sluggable;

use Domain\Shared\Models\BaseModel;
use Domain\Shared\Observers\RedisCache\RedisCacheable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogCategory extends BaseModel
{
    use Sluggable, RedisCacheable;

    public const TYPE_RESOURCE = 'blogCategories';

    protected $fillable = ['parent_id','name','slug','description','active'];

    public function blogPosts(): HasMany
    {
        return $this->hasMany(BlogPost::class);
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
