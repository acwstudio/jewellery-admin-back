<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Models;

use Database\Factories\Modules\Catalog\ProductImageUrlFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenSearch\ScoutDriverPlus\Searchable;

/**
 * @property int $id
 * @property int $product_id
 * @property string $path
 * @property bool $is_main
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @method static self|null find(int $id)
 * @method static self findOrFail(int $id)
 */
class ProductImageUrl extends Model
{
    use HasFactory;
    use Searchable;

    protected $table = 'catalog.product_image_urls';

    protected $fillable = ['path', 'is_main'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    protected static function newFactory()
    {
        return app(ProductImageUrlFactory::class);
    }
}
