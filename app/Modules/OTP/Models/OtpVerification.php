<?php

declare(strict_types=1);

namespace App\Modules\OTP\Models;

use App\Packages\Support\PhoneNumber;
use Database\Factories\Modules\OTP\OtpVerificationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property string $id
 * @property string $code
 * @property PhoneNumber $phone
 * @property bool $confirmed
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @method static self|null find(string $id)
 * @method static self findOrFail(string $id)
 */
class OtpVerification extends Model
{
    use HasFactory;

    protected $table = 'otp.otp_verifications';

    protected $casts = [
        'phone' => PhoneNumber::class,
    ];

    protected $fillable = [
        'code', 'phone', 'confirmed'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($otpVerification) {
            $otpVerification->{$otpVerification->getKeyName()} = (string) Str::uuid();
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

    protected static function newFactory()
    {
        return app(OtpVerificationFactory::class);
    }
}
