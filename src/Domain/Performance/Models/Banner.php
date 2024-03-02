<?php

namespace Domain\Performance\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Domain\Shared\Models\BaseModel;
use Domain\Shared\Observers\RedisCache\RedisCacheable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Banner extends BaseModel
{
    use Sluggable, RedisCacheable;

    public const TYPE_RESOURCE = 'banners';

    protected $fillable = ['type_banner_id','name','description','slug','link','is_active'];

    public function typeBanner(): BelongsTo
    {
        return $this->belongsTo(TypeBanner::class);
    }

    public function typePage(): BelongsTo
    {
        return $this->belongsTo(TypePage::class);
    }

    public function imageBanners(): BelongsToMany
    {
        return $this->belongsToMany(ImageBanner::class);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'resource' => 'name'
            ]
        ];
    }
}
