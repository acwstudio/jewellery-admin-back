<?php

declare(strict_types=1);

namespace App\Modules\Collections\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $collection_id
 * @property int $product_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \App\Modules\Collections\Models\Collection $collection
 *
 * @method static self|null find(int $id)
 * @method static self findOrFail(int $id)
 */
class CollectionProductListItem extends Model
{
    use HasFactory;

    protected $table = 'collections.collection_products';
    protected $with = ['collection'];

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class, 'collection_id');
    }
}
