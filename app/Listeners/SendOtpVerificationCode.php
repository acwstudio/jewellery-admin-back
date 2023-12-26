<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Modules\Messages\MessageGateway\MessageGateway;
use App\Packages\DataObjects\OTP\OtpVerificationData;
use App\Packages\Events\OtpVerificationCreated;
use App\Packages\ModuleClients\OtpModuleClientInterface;

class SendOtpVerificationCode
{
    public function __construct(
        private readonly MessageGateway $messageGateway,
        private readonly OtpModuleClientInterface $otpModuleClient
    ) {
    }

    public function handle(OtpVerificationCreated $event): void
    {
        if (config('otp.static_code_enabled')) {
            return;
        }

        $otpVerification = $this->otpModuleClient->getOtpVerification(
            $event->otpVerificationId
        );

        if (!$otpVerification instanceof OtpVerificationData) {
            return;
        }

        $this->messageGateway->sendSms(
            $otpVerification->phone,
            __('messages.sms.send_otp_verification_code', [
                'code' => $event->code
            ])
        );
    }
}
