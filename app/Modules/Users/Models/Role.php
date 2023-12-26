<?php

declare(strict_types=1);

namespace App\Modules\Users\Models;

use App\Packages\Enums\Users\RoleEnum;
use Database\Factories\Modules\Users\RoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property RoleEnum $type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Role extends Model
{
    use HasFactory;

    protected $table = 'users.roles';

    protected $fillable = [
        'type'
    ];

    protected $casts = [
        'type' => RoleEnum::class,
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'users.user_roles',
            'role_id',
            'user_id'
        );
    }

    protected static function newFactory()
    {
        return app(RoleFactory::class);
    }
}
