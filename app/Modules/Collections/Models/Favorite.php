<?php

declare(strict_types=1);

namespace App\Modules\Collections\Models;

use Database\Factories\Modules\Collections\FavoriteFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property string $description
 * @property string $background_color
 * @property string $font_color
 * @property \App\Modules\Collections\Models\Collection $collection
 * @property \App\Modules\Collections\Models\File $image
 * @property \App\Modules\Collections\Models\File $imageMob
 *
 * @method static self|null find(int $id)
 * @method static self findOrFail(int $id)
 */
class Favorite extends Model
{
    use HasFactory;

    protected $table = 'collections.favorites';
    protected $with = ['image', 'imageMob', 'collection'];
    protected $fillable = [
        'slug', 'name', 'description', 'background_color', 'font_color'
    ];

    public function image(): BelongsTo
    {
        return $this->belongsTo(File::class, 'image_id');
    }

    public function imageMob(): BelongsTo
    {
        return $this->belongsTo(File::class, 'image_mob_id');
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class, 'collection_id');
    }

    protected static function newFactory()
    {
        return app(FavoriteFactory::class);
    }
}
