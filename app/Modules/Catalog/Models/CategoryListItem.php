<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Models;

use App\Packages\Concerns\PreventsModelEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int|null $parent_id
 * @property string $title
 * @property string $h1
 * @property string $description
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $external_id
 * @property string $slug
 * @property self[] $children
 * @property self $parent
 *
 * @property PreviewImage|null $previewImage
 *
 * @method static self|null find(int $id)
 * @method static self findOrFail(int $id)
 */
class CategoryListItem extends Model
{
    use PreventsModelEvents;

    protected $table = 'catalog.categories';

    protected $with = ['children', 'slugAliases', 'previewImage'];

    protected static array $prevents = ['updating', 'updated', 'saving', 'saved', 'deleting', 'deleted'];

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function slugAliases(): HasMany
    {
        return $this->hasMany(CategorySlugAlias::class, 'category_id');
    }

    public function previewImage(): BelongsTo
    {
        return $this->belongsTo(PreviewImage::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'catalog.product_categories',
            'category_id',
            'product_id'
        )->withTimestamps();
    }
}
