<?php

declare(strict_types=1);

namespace App\Modules\OTP\Repositories;

use App\Modules\OTP\Models\OtpVerification;
use App\Packages\Support\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class OtpVerificationRepository
{
    public function getById(string $id, bool $fail = false): ?OtpVerification
    {
        if ($fail) {
            return OtpVerification::findOrFail($id);
        }
        return OtpVerification::find($id);
    }

    public function getForCheck(string $id, bool $fail = false): ?OtpVerification
    {
        $query = OtpVerification::query()->where('confirmed', '=', false);

        if ($fail) {
            /** @var OtpVerification $otpVerification */
            $otpVerification = $query->findOrFail($id);
            return $otpVerification;
        }

        /** @var OtpVerification|null $otpVerification */
        $otpVerification = $query->find($id);
        return $otpVerification;
    }

    public function getByPhone(PhoneNumber $phone): ?OtpVerification
    {
        $phoneFormat = PhoneNumberUtil::getInstance()->format(
            $phone,
            PhoneNumberFormat::E164
        );

        $query = OtpVerification::query()
            ->where('phone', '=', $phoneFormat)
            ->orderBy('created_at', 'desc');

        /** @var OtpVerification|null $otpVerification */
        $otpVerification = $query->first();

        return $otpVerification;
    }

    public function create(PhoneNumber $phone, string $code): OtpVerification
    {
        $otpVerification = new OtpVerification([
            'code' => $code,
            'phone' => $phone,
            'confirmed' => false
        ]);

        $otpVerification->save();

        return $otpVerification;
    }

    public function setConfirmed(OtpVerification $otpVerification): void
    {
        $otpVerification->update(['confirmed' => true]);
    }

    public function delete(OtpVerification $otpVerification): void
    {
        $otpVerification->delete();
    }
}
