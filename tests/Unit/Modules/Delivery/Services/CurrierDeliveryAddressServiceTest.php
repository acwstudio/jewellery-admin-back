<?php

declare(strict_types=1);

namespace Modules\Delivery\Services;

use App\Modules\Delivery\Models\CurrierDeliveryAddress;
use App\Modules\Delivery\Services\CurrierDeliveryAddressService;
use App\Modules\Users\Models\User;
use App\Packages\DataObjects\Delivery\SavedAddressData;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CurrierDeliveryAddressServiceTest extends TestCase
{
    private CurrierDeliveryAddressService $addressService;
    private User $user;


    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->getUser();
        Sanctum::actingAs($this->user);
        $this->addressService = app(CurrierDeliveryAddressService::class);
    }

    public function testSuccessfulGet()
    {
        $address = CurrierDeliveryAddress::factory()->create([
            'user_id' => $this->user->user_id
        ]);
        $newAddress = $address->replicate();
        $newAddress->save();

        $addresses = $this->addressService->get()->map(function (CurrierDeliveryAddress $address) {
            return new SavedAddressData(
                $address->id,
                $address->address
            );
        });

        $this->assertCount(0, $addresses->duplicates());
    }
}
