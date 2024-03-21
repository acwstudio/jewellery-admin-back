<?php

namespace Domain\Catalog\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Domain\Shared\Models\BaseModel;
use Domain\Shared\Observers\RedisCache\RedisCacheable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

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

    public function prices(): HasManyThrough
    {
        return $this->hasManyThrough(Price::class, Size::class, 'size_category_id', 'size_id', 'id', 'id');
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
