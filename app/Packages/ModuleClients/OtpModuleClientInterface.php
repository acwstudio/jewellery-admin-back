<?php

declare(strict_types=1);

namespace App\Packages\ModuleClients;

use App\Packages\DataObjects\OTP\CheckOtpVerificationData;
use App\Packages\DataObjects\OTP\OtpVerificationData;
use App\Packages\DataObjects\OTP\SendOtpVerificationData;

interface OtpModuleClientInterface
{
    public function getOtpVerification(string $id): ?OtpVerificationData;

    public function send(SendOtpVerificationData $data): OtpVerificationData;

    public function check(CheckOtpVerificationData $data): bool;
}
