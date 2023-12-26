<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Models;

use Carbon\Carbon;
use Database\Factories\Modules\Catalog\BrandFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OpenSearch\ScoutDriverPlus\Searchable;

/**
 * @class Brand
 * @property int $id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Brand extends Model
{
    use HasFactory;
    use Searchable;

    protected $table = 'catalog.brands';
    protected $fillable = ['name'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    protected static function newFactory()
    {
        return app(BrandFactory::class);
    }
}
