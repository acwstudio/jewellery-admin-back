<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Delivery\Support\DataNormalizer;

use App\Modules\Delivery\Support\Pvz\DataNormalizer\Enterprise1CPvzDataNormalizer;
use App\Modules\Delivery\Support\Pvz\DataNormalizer\PvzDataNormalizerInterface;
use App\Packages\DataObjects\Delivery\ImportPvzData;
use Tests\TestCase;

class Enterprise1CPvzDataNormalizerTest extends TestCase
{
    private PvzDataNormalizerInterface $pvzDataNormalizer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pvzDataNormalizer = new Enterprise1CPvzDataNormalizer();
    }

    public function testSuccessful()
    {
        $result = $this->pvzDataNormalizer->normalize($this->getData());
        self::assertInstanceOf(ImportPvzData::class, $result);
    }

    public function testSuccessfulDel()
    {
        $data = $this->getData();
        $data['del'] = true;

        $result = $this->pvzDataNormalizer->normalize($data);
        self::assertInstanceOf(ImportPvzData::class, $result);
        self::assertTrue($result->delete);

        $data['del'] = false;

        $result = $this->pvzDataNormalizer->normalize($data);
        self::assertInstanceOf(ImportPvzData::class, $result);
        self::assertFalse($result->delete);
    }

    private function getData(): array
    {
        return json_decode(
            file_get_contents($this->getTestResources('test_PVZ_1C-Site.json')),
            true
        );
    }
}
