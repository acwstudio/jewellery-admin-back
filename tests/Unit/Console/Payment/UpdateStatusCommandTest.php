<?php

declare(strict_types=1);

namespace Console\Payment;

use App\Console\Commands\Payment\UpdateStatusCommand;
use App\Modules\Payment\Enums\PaymentStatusEnum;
use App\Modules\Payment\Enums\PaymentTypeEnum;
use App\Modules\Payment\Repositories\PaymentRepository;
use App\Packages\ApiClients\Payment\ApiSberClient;
use App\Packages\Events\UpdateStatusCommandHasFailed;
use App\Packages\ModuleClients\ApiSberClientInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Event;
use Tests\SberTestCase;

class UpdateStatusCommandTest extends SberTestCase
{
    private const GET_ORDER_STATUS_EXTENDED = 'getOrderStatusExtended';

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake([
            UpdateStatusCommandHasFailed::class,
        ]);
    }

    /**
     * @test
     */
    public function itCanUseCustomStatuses(): void
    {
        $statuses = [
            PaymentStatusEnum::DECLINED->value,
            PaymentStatusEnum::REVERSED->value,
            PaymentStatusEnum::REFUNDED->value,
        ];
        $this->mockAcquiringPaymentRepository($statuses, new Collection());
        $this->mockClient();
        $this->artisan('sberbank-acquiring:update-statuses', ['--id' => [8, 5, 6]])->assertExitCode(0);
    }

    /**
     * @test
     */
    public function itCanUseDefaultStatuses(): void
    {
        $this->mockAcquiringPaymentRepository(
            UpdateStatusCommand::STATUSES,
            new Collection(),
        );
        $this->mockClient();
        $this->artisan('sberbank-acquiring:update-statuses')
            ->assertExitCode(0);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function itUpdatesPaymentsStatuses(): void
    {
        /** @var array<int> $payments */
        $payments = [
            $this->createAcquiringPayment(PaymentTypeEnum::APPLE_PAY, ['status_id' => PaymentStatusEnum::NEW])->id,
            $this->createAcquiringPayment(PaymentTypeEnum::SAMSUNG_PAY, ['status_id' => PaymentStatusEnum::NEW])->id,
            $this->createAcquiringPayment(PaymentTypeEnum::SBER_PAY, ['status_id' => PaymentStatusEnum::NEW])->id,
            $this->createAcquiringPayment(PaymentTypeEnum::GOOGLE_PAY, ['status_id' => PaymentStatusEnum::NEW])->id,
        ];
        $this->mockApiSberClient($payments);
        $this->artisan('sberbank-acquiring:update-statuses', [
            '--id' => [1, 5, 2],
        ])->assertExitCode(1);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function itShouldFailAndEmitEventWhenGetOrderStatusThrowsException(): void
    {
        /** @var array<int> $payments */
        $payments = [
            $this->createAcquiringPayment(PaymentTypeEnum::SBER_PAY, ['status_id' => PaymentStatusEnum::NEW])->id,
            $this->createAcquiringPayment(PaymentTypeEnum::SBER_PAY, ['status_id' => PaymentStatusEnum::REFUNDED])->id,
            $this->createAcquiringPayment(PaymentTypeEnum::SBER_PAY, ['status_id' => PaymentStatusEnum::ACS_AUTH])->id,
        ];
        $this->mockApiSberClient($payments);
        $commandUpdateStatuses = $this->artisan(
            'sberbank-acquiring:update-statuses',
            [
                '--id' => [1, 6, 7],
            ]
        );
        $commandUpdateStatuses->assertExitCode(1);
        $statusCode = $commandUpdateStatuses->execute();
        Event::assertDispatched(UpdateStatusCommandHasFailed::class, $statusCode);
    }

    private function mockAcquiringPaymentRepository(array $args, $returnValue): void
    {
        $this->mock(
            PaymentRepository::class,
            function ($mock) use ($args, $returnValue) {
                $mock->expects('getByStatus')->with($args)->andReturn($returnValue)->once();
            }
        );
    }

    private function mockClient(): void
    {
        $this->instance(
            ApiSberClientInterface::class,
            $this->getMockBuilder(ApiSberClient::class)->getMock()
        );
    }

    private function mockApiSberClient($payments): void
    {
        $this->mock(ApiSberClient::class, function ($mock) use ($payments) {
            foreach ($payments as $id) {
                $mock->allows(something: self::GET_ORDER_STATUS_EXTENDED)->with($id)->andReturns();
            }
        });
        //        $this->mock(ApiSberClientInterface::class, function (MockInterface $mock) use ($payments) {
        //            foreach ($payments as $id) {
        //                $mock->allows(self::GET_ORDER_STATUS_EXTENDED)->with($id)->andReturns();
        //            }
        //        });
    }
}
