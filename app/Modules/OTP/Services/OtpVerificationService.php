<?php

declare(strict_types=1);

namespace App\Modules\OTP\Services;

use App\Modules\OTP\Models\OtpVerification;
use App\Modules\OTP\Repositories\OtpVerificationRepository;
use App\Packages\DataObjects\OTP\CheckOtpVerificationData;
use App\Packages\Events\OtpVerificationCreated;
use App\Packages\Support\PhoneNumber;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Psr\Log\LoggerInterface;

class OtpVerificationService
{
    public function __construct(
        private readonly OtpVerificationRepository $otpVerificationRepository
    ) {
    }

    public function get(string $id): ?OtpVerification
    {
        return $this->otpVerificationRepository->getById($id);
    }

    public function create(PhoneNumber $phone): OtpVerification
    {
        if (!$this->retryCreate($phone)) {
            throw new \Exception('Превышен лимит отправки кода');
        }

        $code = $this->generateCode();
        $otpVerification = $this->otpVerificationRepository->create($phone, $code);

        App::make(LoggerInterface::class)->info('[rapporto] SendOtpVerificationCode::handle');

        OtpVerificationCreated::dispatch(
            $otpVerification->id,
            $code
        );

        return $otpVerification;
    }

    public function check(CheckOtpVerificationData $checkOtpVerificationData): bool
    {
        $otpVerification = $this->otpVerificationRepository->getForCheck($checkOtpVerificationData->id);

        if (!$otpVerification instanceof OtpVerification) {
            return false;
        }

        if (!$otpVerification->phone->equals($checkOtpVerificationData->phone)) {
            return false;
        }

        if ($otpVerification->code !== $checkOtpVerificationData->code) {
            return false;
        }

        $this->otpVerificationRepository->setConfirmed($otpVerification);

        return true;
    }

    private function generateCode(): string
    {
        /** Дебаг режим для OTP */
        if (config('otp.static_code_enabled')) {
            return config('otp.static_code_value');
        }

        $lenCode = config('otp.len_code');

        $a = '1';
        $b = '9';

        for ($i = 1; $i < $lenCode; $i++) {
            $a = $a . '0';
            $b = $b . '0';
        }

        return strval(rand((int)$a, (int)$b));
    }

    private function retryCreate(PhoneNumber $phone): bool
    {
        $otpVerification = $this->otpVerificationRepository->getByPhone($phone);

        if (!$otpVerification instanceof OtpVerification) {
            return true;
        }

        $retryTimeout = Carbon::now()->subSeconds(config('otp.retry_timeout'));

        if ($otpVerification->created_at->lt($retryTimeout)) {
            return true;
        }

        return false;
    }
}
