<?php

declare(strict_types=1);

namespace Modules\Delivery\Filter;

use App\Modules\Delivery\Models\Pvz;
use App\Modules\Delivery\Support\Pvz\Filter\Passable;
use App\Modules\Delivery\Support\Pvz\Filter\StreetFilterPipe;
use App\Packages\DataObjects\Delivery\GetPvz\GetPvzAddressFilterData;
use App\Packages\DataObjects\Delivery\GetPvz\GetPvzFilterData;
use Illuminate\Routing\Pipeline;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class StreetFilterPipeTest extends TestCase
{
    private array $filterPipes;

    protected function setUp(): void
    {
        parent::setUp();

        $this->filterPipes = [
            StreetFilterPipe::class
        ];
    }

    public function testSuccefulFiltered(): void
    {
        $pvz = Pvz::factory(5)->create();
        /** @var Pvz $firstPvz */
        $firstPvz = $pvz->first();
        $city = $firstPvz->city;
        $street = $firstPvz->street;

        $pipeline = App::make(Pipeline::class);
        $filterData = new GetPvzFilterData(address: new GetPvzAddressFilterData(city: $city, street: $street));

        $passable = new Passable($pvz->collect()->flatten(), $filterData);

        $filteredResult = $this->getFilteredResult($pipeline, $passable);

        /** @var Pvz $resultPvz */
        foreach ($filteredResult as $resultPvz) {
            $this->assertEquals($city, $resultPvz->city);
            $this->assertEquals($street, $resultPvz->street);
        }
    }

    private function getFilteredResult(Pipeline $pipeline, Passable $passable)
    {
        return $pipeline->send($passable)->through($this->filterPipes)->then(function (Passable $passable) {
            return $passable->pvz;
        });
    }
}
