<?php

declare(strict_types=1);

namespace App\Modules\OTP;

use App\Modules\OTP\Models\OtpVerification;
use App\Modules\OTP\Services\OtpVerificationService;
use App\Packages\ApiClients\Recaptcha\RecaptchaApiClient;
use App\Packages\DataObjects\OTP\CheckOtpVerificationData;
use App\Packages\DataObjects\OTP\OtpVerificationData;
use App\Packages\DataObjects\OTP\SendOtpVerificationData;
use App\Packages\ModuleClients\OtpModuleClientInterface;

final class OtpModuleClient implements OtpModuleClientInterface
{
    public function __construct(
        private readonly OtpVerificationService $otpVerificationService,
        private readonly RecaptchaApiClient $recaptchaApiClient
    ) {
    }

    public function getOtpVerification(string $id): ?OtpVerificationData
    {
        $otpVerification = $this->otpVerificationService->get($id);

        if (!$otpVerification instanceof OtpVerification) {
            return null;
        }

        return OtpVerificationData::fromModel($otpVerification);
    }

    public function send(SendOtpVerificationData $data): OtpVerificationData
    {
        $responseRecaptcha = $this->recaptchaApiClient->siteVerify($data->recaptcha_token);
        if (!$responseRecaptcha->success) {
            throw new \Exception('Ошибка Recaptcha');
        }

        $otpVerification = $this->otpVerificationService->create($data->phone);

        return OtpVerificationData::fromModel($otpVerification);
    }

    public function check(CheckOtpVerificationData $data): bool
    {
        return $this->otpVerificationService->check(
            new CheckOtpVerificationData(
                $data->id,
                $data->phone,
                $data->code
            )
        );
    }
}
