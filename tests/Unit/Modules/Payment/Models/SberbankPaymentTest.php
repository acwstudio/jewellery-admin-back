<?php

declare(strict_types=1);

namespace Modules\Payment\Models;

use App\Modules\Payment\Enums\PaymentTypeEnum;
use App\Modules\Payment\Helpers\Currency;
use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Models\SberbankPayment;
use Tests\SberTestCase;

class SberbankPaymentTest extends SberTestCase
{
    /**
     * @test
     * @throws \Exception
     */
    public function itHasBasePaymentRelation(): void
    {
        $acquiringPayment = $this->createAcquiringPayment(PaymentTypeEnum::SBER_PAY);
        $sberbankPayment = $this->createSberbankPayment();
        $acquiringPayment->payment()->associate($sberbankPayment);
        $acquiringPayment->save();
        $this->assertInstanceOf(Payment::class, $sberbankPayment->basePayment);
    }

    /**
     * @throws \JsonException
     */
    public function testItCanBeFilledWithSberbankAttributes(): void
    {
        $params = [
           'amount'             => 7531,
           'currency'           => Currency::EUR,
           'return_url'          => 'http://test.com/api/success',
           'fail_url'            => 'http://test.com/api/error',
           'description'        => 'operation description',
           'language'           => 'EN',
           'client_id'           => 'b4vcm251vcxs',
           'page_view'           => 'mobile',
           'json_params'         => ['foo' => 'bar'],
           'session_timeout_secs' => 1000,
           'expiration_date'     => '20201231',
           'features'           => 'operation features',
        ];
        $expectedAttributes = [
            'amount'               => 7531,
            'currency'             => Currency::EUR,
            'return_url'           => 'http://test.com/api/success',
            'fail_url'             => 'http://test.com/api/error',
            'description'          => 'operation description',
            'language'             => 'EN',
            'client_id'            => 'b4vcm251vcxs',
            'page_view'            => 'mobile',
            'json_params'          => json_encode(['foo' => 'bar'], JSON_THROW_ON_ERROR),
            'session_timeout_secs' => 1000,
            'expiration_date'      => '20201231',
            'features'             => 'operation features',
        ];
        $payment = new SberbankPayment();
        $payment->fillWithSberbankParams($params);
        $this->assertEquals($expectedAttributes, $payment->getAttributes());
    }
}
