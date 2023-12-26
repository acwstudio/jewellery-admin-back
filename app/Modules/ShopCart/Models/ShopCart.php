<?php

declare(strict_types=1);

namespace App\Modules\ShopCart\Models;

use Database\Factories\Modules\ShopCart\ShopCartFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $token
 * @property string|null $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property \Illuminate\Support\Collection<ShopCartItem> $items
 *
 * @method static self|null find(int $id)
 * @method static self findOrFail(int $id)
 */
class ShopCart extends Model
{
    use HasFactory;

    protected $table = 'shop_cart.shop_carts';

    protected $fillable = ['user_id'];

    protected static function boot()
    {
        parent::boot();
        static::updating(function (ShopCart $model) {
            if (
                !empty($model->user_id)
                && ShopCart::query()
                    ->where('user_id', '=', $model->user_id)
                    ->where($model->getKeyName(), '!=', $model->{$model->getKeyName()})
                    ->exists()
            ) {
                throw new \Exception('Дублирующая корзина у пользователя');
            }
        });

        static::creating(function (ShopCart $model) {
            $model->token = Str::uuid()->toString();

            if (
                !empty($model->user_id)
                && ShopCart::query()->where('user_id', '=', $model->user_id)->exists()
            ) {
                throw new \Exception('Дублирующая корзина у пользователя');
            }
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(ShopCartItem::class);
    }

    protected static function newFactory()
    {
        return app(ShopCartFactory::class);
    }
}
