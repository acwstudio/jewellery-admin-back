<?php

declare(strict_types=1);

namespace App\Modules\Users\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

/**
 * @property int $id
 * @property string $tokenable_type
 * @property string $tokenable_id
 * @property string $name
 * @property string $token
 * @property string|null $abilities
 * @property \Carbon\Carbon|null $last_used_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class PersonalAccessToken extends SanctumPersonalAccessToken
{
    protected $table = 'users.personal_access_tokens';
}
