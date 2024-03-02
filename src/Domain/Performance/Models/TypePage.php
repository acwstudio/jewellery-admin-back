<?php

namespace Domain\Performance\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Domain\Shared\Models\BaseModel;
use Domain\Shared\Observers\RedisCache\RedisCacheable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypePage extends BaseModel
{
    use Sluggable, RedisCacheable;

    public const TYPE_RESOURCE = 'typePages';

    protected $fillable = ['type','slug','is_active'];

    public function banners(): HasMany
    {
        return $this->hasMany(Banner::class);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'resource' => 'type'
            ]
        ];
    }
}
