<?php

namespace Domain\Performance\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Domain\Shared\Models\BaseModel;
use Domain\Shared\Observers\RedisCache\RedisCacheable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeDevice extends BaseModel
{
    use Sluggable, RedisCacheable;

    public const TYPE_RESOURCE = 'typeDevices';

    protected $fillable = ['type','slug','is_active'];

    public function imageBanners(): HasMany
    {
        return $this->hasMany(ImageBanner::class);
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
