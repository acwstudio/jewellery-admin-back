<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Catalog\Support;

use App\Modules\Catalog\Support\SlugGenerator;
use Tests\TestCase;

class SlugGeneratorTest extends TestCase
{
    private SlugGenerator $slugGenerator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->slugGenerator = app(SlugGenerator::class);
    }

    public function testSuccessfulCreateForProduct()
    {
        $name = 'Серебряная подвеска';
        $sku = '0236094';

        $result = $this->slugGenerator->createForProduct($name, $sku);
        self::assertEquals(
            'serebrianaia-podveska_0236094',
            $result
        );

        $name = 'Серебряная подвеска 925 пробы; "Матрона Московская"; вставки 4 Рубин нат. 3/5 1,108;';
        $sku = '02Л36094';

        $result = $this->slugGenerator->createForProduct($name, $sku);
        self::assertEquals(
            'serebrianaia-podveska-925-proby-matrona-moskovskaia-vstavki-4-rubin-nat-35-1108_02l36094',
            $result
        );
    }
}
