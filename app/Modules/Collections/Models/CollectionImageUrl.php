<?php

declare(strict_types=1);

namespace App\Modules\Collections\Models;

use App\Modules\Collections\Enums\CollectionImageUrlTypeEnum;
use Database\Factories\Modules\Collections\CollectionImageUrlFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $collection_id
 * @property string $path
 * @property CollectionImageUrlTypeEnum $type
 *
 * @property Collection $collection
 *
 * @method static self|null find(int $id)
 * @method static self findOrFail(int $id)
 */
class CollectionImageUrl extends Model
{
    use HasFactory;

    protected $table = 'collections.collection_image_urls';
    protected $fillable = ['path', 'type'];

    protected $casts = [
        'type' => CollectionImageUrlTypeEnum::class
    ];

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    protected static function newFactory()
    {
        return app(CollectionImageUrlFactory::class);
    }
}
