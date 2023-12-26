<?php

declare(strict_types=1);

namespace App\Modules\Payment\Traits;

/**
 * @property string|null $payment_token
 */
trait HasPaymentToken
{
    /**
     * @param  string  $token
     *
     * @return void
     */
    public function setPaymentToken(string $token): void
    {
        $this->payment_token = $token;
    }

    public function getPaymentToken(): string
    {
        return $this->payment_token ?? '';
    }
}
