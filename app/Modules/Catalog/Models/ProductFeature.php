<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Models;

use Database\Factories\Modules\Catalog\ProductFeatureFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use OpenSearch\ScoutDriverPlus\Searchable;

/**
 * @property string $uuid
 * @property int $product_id
 * @property int $feature_id
 * @property string|null $parent_uuid
 * @property string|null $value
 * @property boolean $is_main
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 *
 * @property Product $product
 * @property Feature $feature
 * @property \Illuminate\Support\Collection<ProductFeature> $children
 * @property self|null $parent
 *
 * @method static self|null find(string $uuid)
 * @method static self findOrFail(string $uuid)
 */
class ProductFeature extends Model
{
    use HasFactory;
    use Searchable;

    protected $table = 'catalog.product_features';
    protected $with = ['feature', 'children'];
    protected $fillable = ['value', 'is_main'];
    protected $primaryKey = 'uuid';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    public function getIncrementing()
    {
        return false;
    }
    public function getKeyType()
    {
        return 'string';
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function feature(): BelongsTo
    {
        return $this->belongsTo(Feature::class, 'feature_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_uuid');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_uuid');
    }

    protected static function newFactory()
    {
        return app(ProductFeatureFactory::class);
    }
}
