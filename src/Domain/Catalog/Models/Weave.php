<?php

namespace Domain\Catalog\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Domain\Shared\Models\BaseModel;
use Domain\Shared\Observers\RedisCache\RedisCacheable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Weave extends BaseModel
{
    use Sluggable, RedisCacheable;

    public const TYPE_RESOURCE = 'weaves';

    protected $fillable = ['name','slug','is_active'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
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
