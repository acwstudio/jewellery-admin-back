<?php

declare(strict_types=1);

namespace App\Modules\Users\Models;

use Database\Factories\Modules\Users\WishlistProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * @property string $uuid
 * @property string $user_id
 * @property int $product_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property User $user
 *
 * @method static self|null find(string $uuid)
 * @method static self findOrFail(string $uuid)
 */
class WishlistProduct extends Model
{
    use HasFactory;

    protected $table = 'users.wishlist_products';

    protected $primaryKey = 'uuid';

    protected $fillable = ['product_id'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function (WishlistProduct $model) {
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function newFactory()
    {
        return app(WishlistProductFactory::class);
    }
}
