<?php

declare(strict_types=1);

namespace App\Modules\Users\Models;

use App\Modules\Delivery\Models\CurrierDeliveryAddress;
use App\Modules\Delivery\Models\Pvz;
use App\Modules\Orders\Models\Order;
use App\Modules\Users\Enums\SexTypeEnum;
use App\Packages\Enums\Users\RoleEnum;
use App\Packages\Support\PhoneNumber;
use Carbon\Carbon;
use Database\Factories\Modules\Users\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

/**
 * @property string $user_id
 * @property string|null $name
 * @property string|null $surname
 * @property string|null $patronymic
 * @property PhoneNumber|null $phone
 * @property string|null $email
 * @property string|null $password
 * @property string|null $remember_token
 * @property SexTypeEnum|null $sex
 * @property Carbon|null $birth_date
 * @property \DateTime|null $email_verified_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property Collection $currierDeliveryAddresses
 * @property Collection $pvz
 *
 * @method static self|null find(string $id)
 * @method static self findOrFail(string $id)
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected $table = 'users.users';
    protected $primaryKey = 'user_id';

    protected static function boot()
    {
        parent::boot();
        static::creating(function (User $user) {
            $user->{$user->getKeyName()} = (string) Str::uuid();
            if (
                !empty($user->phone)
                && User::query()->where(
                    'phone',
                    PhoneNumberUtil::getInstance()->format($user->phone, PhoneNumberFormat::E164)
                )->exists()
            ) {
                throw new \Exception('Пользователь с таким телефоном уже существует');
            }
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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'phone',
        'name',
        'surname',
        'patronymic',
        'sex',
        'birth_date',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone' => PhoneNumber::class,
        'sex' => SexTypeEnum::class,
        'birth_date' => 'datetime'
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'users.user_roles',
            'user_id',
            'role_id'
        );
    }

    public function hasRole(string $role): bool
    {
        /** @phpstan-ignore-next-line */
        $roles = $this->roles()->where('type', '=', RoleEnum::tryFrom($role))->get();
        if ($roles->count() === 1) {
            return true;
        }

        return false;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(RoleEnum::ADMIN->value);
    }

    public function wishlistProducts(): HasMany
    {
        return $this->hasMany(WishlistProduct::class, 'user_id');
    }

    public function currierDeliveryAddresses(): HasMany
    {
        return $this->hasMany(CurrierDeliveryAddress::class, 'user_id');
    }

    public function pvz(): BelongsToMany
    {
        return $this->belongsToMany(Pvz::class, 'delivery.user_pvz', 'user_id', 'pvz_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, foreignKey: 'user_id');
    }

    protected static function newFactory()
    {
        return app(UserFactory::class);
    }
}
