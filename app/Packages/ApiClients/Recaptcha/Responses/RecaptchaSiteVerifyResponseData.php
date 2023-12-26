<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Recaptcha\Responses;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class RecaptchaSiteVerifyResponseData extends Data
{
    public function __construct(
        public readonly bool $success,
        #[MapInputName('error-codes')]
        public readonly ?array $error_codes = null,
        public readonly ?string $challenge_ts = null,
        public readonly ?string $hostname = null,
    ) {
    }
}
