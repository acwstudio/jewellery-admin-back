<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Models;

use App\Modules\Catalog\Enums\FeatureTypeEnum;
use Database\Factories\Modules\Catalog\FeatureFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OpenSearch\ScoutDriverPlus\Searchable;

/**
 * @property int $id
 * @property FeatureTypeEnum $type
 * @property string $value
 * @property string $slug
 * @property integer|null $position
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @method static self|null find(int $id)
 * @method static self findOrFail(int $id)
 */
class Feature extends Model
{
    use HasFactory;
    use Searchable;

    protected $table = 'catalog.features';

    protected $fillable = ['type', 'value', 'slug', 'position'];

    protected $casts = [
        'type' => FeatureTypeEnum::class,
    ];

    public function productFeatures(): HasMany
    {
        return $this->hasMany(ProductFeature::class, 'feature_id');
    }

    protected static function newFactory()
    {
        return app(FeatureFactory::class);
    }
}
