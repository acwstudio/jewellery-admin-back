<?php

declare(strict_types=1);

namespace App\Modules\Users\Support\Blueprints;

use App\Packages\Support\PhoneNumber;

class AuthBlueprint
{
    public function __construct(
        public readonly ?PhoneNumber $phone = null,
        public readonly ?string $otp_id = null,
        public readonly ?string $otp_code = null,
        public readonly ?string $email = null,
        public readonly ?string $password = null,
    ) {
    }
}
