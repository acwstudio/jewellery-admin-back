<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Models;

use Database\Factories\Modules\Catalog\SeoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int|null $parent_id
 * @property int $category_id
 * @property string $url
 * @property array $filters
 * @property string $h1
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property \App\Modules\Catalog\Models\Category $category
 * @property self|null $parent
 * @property \Illuminate\Support\Collection<self> $children
 *
 * @method static self|null find(int $id)
 * @method static self findOrFail(int $id)
 */
class Seo extends Model
{
    use HasFactory;

    protected $table = 'catalog.seo';

    protected $fillable = ['url', 'filters', 'h1', 'meta_title', 'meta_description'];

    protected $casts = [
        'filters' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    protected static function newFactory()
    {
        return app(SeoFactory::class);
    }
}
