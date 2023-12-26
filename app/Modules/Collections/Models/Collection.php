<?php

declare(strict_types=1);

namespace App\Modules\Collections\Models;

use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Product;
use Database\Factories\Modules\Collections\CollectionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property string $description
 * @property string|null $extended_name
 * @property string|null $extended_description
 * @property File|null $previewImage
 * @property File|null $previewImageMob
 * @property File|null $bannerImage
 * @property File|null $bannerImageMob
 * @property File|null $extendedImage
 * @property boolean $is_active
 * @property boolean $is_hidden
 * @property string|null $external_id
 *
 * @property \Illuminate\Support\Collection<Stone> $stones
 * @property \Illuminate\Support\Collection<File> $images
 * @property \Illuminate\Support\Collection<CollectionImageUrl> $imageUrls
 *
 * @method static self|null find(int $id)
 * @method static self findOrFail(int $id)
 */
class Collection extends Model
{
    use HasFactory;

    protected $table = 'collections.collections';
    protected $with = [
        'previewImage', 'previewImageMob', 'bannerImage', 'bannerImageMob',
        'extendedImage', 'stones', 'images', 'imageUrls'
    ];
    protected $fillable = [
        'slug', 'name', 'description', 'extended_name', 'extended_description',
        'is_active', 'is_hidden', 'external_id'
    ];

    public function previewImage(): BelongsTo
    {
        return $this->belongsTo(File::class, 'preview_image_id');
    }

    public function previewImageMob(): BelongsTo
    {
        return $this->belongsTo(File::class, 'preview_image_mob_id');
    }

    public function bannerImage(): BelongsTo
    {
        return $this->belongsTo(File::class, 'banner_image_id');
    }

    public function bannerImageMob(): BelongsTo
    {
        return $this->belongsTo(File::class, 'banner_image_mob_id');
    }

    public function extendedImage(): BelongsTo
    {
        return $this->belongsTo(File::class, 'extended_image_id');
    }

    public function stones(): BelongsToMany
    {
        return $this->belongsToMany(
            Stone::class,
            'collections.collection_stones',
            'collection_id',
            'stone_id'
        )->withTimestamps();
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'collections.collection_products',
            'collection_id',
            'product_id'
        )->withTimestamps();
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            'collections.collection_categories',
            'collection_id',
            'category_id'
        );
    }

    public function images(): BelongsToMany
    {
        return $this->belongsToMany(
            File::class,
            'collections.collection_images',
            'collection_id',
            'image_id'
        );
    }

    public function favorite(): HasOne
    {
        return $this->hasOne(Favorite::class);
    }

    public function imageUrls(): HasMany
    {
        return $this->hasMany(CollectionImageUrl::class);
    }

    protected static function newFactory()
    {
        return app(CollectionFactory::class);
    }
}
