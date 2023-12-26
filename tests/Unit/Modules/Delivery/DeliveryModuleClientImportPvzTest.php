<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Delivery;

use App\Modules\Delivery\Models\Pvz;
use App\Packages\ModuleClients\DeliveryModuleClientInterface;
use Illuminate\Support\Collection;
use Tests\TestCase;

class DeliveryModuleClientImportPvzTest extends TestCase
{
    private DeliveryModuleClientInterface $moduleClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->moduleClient = app(DeliveryModuleClientInterface::class);
    }

    public function testSuccessful()
    {
        $pvzCollection = Pvz::all();
        self::assertTrue($pvzCollection->isEmpty());

        $message = $this->getData();
        $this->mockAMQPModuleClient([$message]);
        $this->moduleClient->importPvz();

        $pvzCollection = Pvz::all();
        self::assertTrue($pvzCollection->isNotEmpty());
        /** @var Pvz $pvz */
        foreach ($pvzCollection as $pvz) {
            self::assertModelExists($pvz);
        }
    }

    public function testSuccessfulByDel()
    {
        $pvzCollection = Pvz::factory(3)->create();

        $message = $this->getDataByPvzCollection($pvzCollection, true);
        $this->mockAMQPModuleClient($message);
        $this->moduleClient->importPvz();

        $pvzCollection = Pvz::all();
        self::assertTrue($pvzCollection->isEmpty());
    }

    private function getDataByPvzCollection(Collection $pvzCollection, bool $isDel = false): array
    {
        $dataCollection = [];
        $template = $this->getData();

        /** @var Pvz $pvz */
        foreach ($pvzCollection as $pvz) {
            $data = $template;
            $data['ID'] = $pvz->carrier->external_id;
            $data['id_pvz'] = $pvz->external_id;
            $data['del'] = $isDel;
            $dataCollection[] = $data;
        }

        return $dataCollection;
    }

    private function getData(): array
    {
        return json_decode(
            file_get_contents($this->getTestResources('test_PVZ_1C-Site.json')),
            true
        );
    }
}
