<?php

namespace Domain\Catalog\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Domain\Shared\Models\BaseModel;
use Domain\Shared\Observers\RedisCache\RedisCacheable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SizeCategory extends BaseModel
{
    use Sluggable, RedisCacheable;

    public const TYPE_RESOURCE = 'sizes';

    protected $fillable = ['type','slug','is_active'];

    public function sizes(): HasMany
    {
        return $this->hasMany(Size::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'sizes');
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'type'
            ]
        ];
    }
}
