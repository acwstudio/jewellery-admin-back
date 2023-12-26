<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Delivery\Services;

use App\Modules\Delivery\Models\CurrierDeliveryAddress;
use App\Modules\Delivery\Models\Pvz;
use App\Modules\Delivery\Services\CurrierDeliveryAddressService;
use App\Modules\Delivery\Services\PvzService;
use App\Modules\Users\Models\User;
use App\Packages\DataObjects\Delivery\SavedAddressData;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PvzServiceTest extends TestCase
{
    private PvzService $pvzService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pvzService = app(PvzService::class);
    }

    public function testSuccessfulGetById()
    {
        /** @var Pvz $pvz */
        $pvz = Pvz::factory()->create();
        $pvzId = $pvz->id;
        $pvz->delete();

        $result = $this->pvzService->getById($pvzId, false);
        $this->assertEmpty($result);

        $result = $this->pvzService->getById($pvzId, false, true);
        $this->assertInstanceOf(Pvz::class, $result);
    }
}
