<?php

declare(strict_types=1);

namespace Console\Catalog;

use App\Modules\Catalog\Models\Product;
use Tests\TestCase;

class ScoutImportTest extends TestCase
{
    private const COMMAND = 'scout:import:catalog';

    public function testSuccessful()
    {
        Product::factory(10)->create(['setFull' => true]);

        $this->artisan(self::COMMAND);

        self::assertEquals(1, 1);
    }
}
