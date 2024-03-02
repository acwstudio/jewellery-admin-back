<?php

namespace Domain\Performance\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Domain\Shared\Models\BaseModel;
use Domain\Shared\Observers\RedisCache\RedisCacheable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ImageBanner extends BaseModel
{
    use Sluggable, RedisCacheable;

    public const TYPE_RESOURCE = 'imageBanners';

    protected $fillable = [
        'type_device_id','name','model_type','description','slug','image_link','is_active','extension','size',
        'mime_type','content_link','sequence'
    ];

    public function typeDevice(): BelongsTo
    {
        return $this->belongsTo(TypeDevice::class);
    }

    public function banners(): BelongsToMany
    {
        return $this->belongsToMany(Banner::class);
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
