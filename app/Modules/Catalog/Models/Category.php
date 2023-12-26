<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Models;

use Database\Factories\Modules\Catalog\CategoryFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OpenSearch\ScoutDriverPlus\Searchable;

/**
 * @property int $id
 * @property string $title
 * @property string $h1
 * @property string $description
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $external_id
 * @property string $slug
 * @property self[] $children
 * @property self|null $parent
 * @property Collection<CategorySlugAlias> $slugAliases
 * @property int|null $preview_image_id
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 *
 * @property PreviewImage|null $previewImage
 *
 * @method static self|null find(int $id)
 * @method static self findOrFail(int $id)
 */
class Category extends Model
{
    use HasFactory;
    use Searchable;

    protected $table = 'catalog.categories';

    protected $fillable = [
        'title', 'h1', 'description', 'meta_title', 'meta_description', 'meta_keywords',
        'external_id', 'slug'
    ];

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function slugAliases(): HasMany
    {
        return $this->hasMany(CategorySlugAlias::class);
    }

    public function previewImage(): BelongsTo
    {
        return $this->belongsTo(PreviewImage::class);
    }

    protected static function newFactory()
    {
        return app(CategoryFactory::class);
    }
}
