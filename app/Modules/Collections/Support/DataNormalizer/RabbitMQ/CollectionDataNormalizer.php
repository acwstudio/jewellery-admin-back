<?php

declare(strict_types=1);

namespace App\Modules\Collections\Support\DataNormalizer\RabbitMQ;

use App\Modules\Collections\Support\DataNormalizer\DataNormalizerInterface;
use App\Packages\DataObjects\Collections\Import\ImportCollectionData;
use Spatie\LaravelData\Data;

class CollectionDataNormalizer implements DataNormalizerInterface
{
    public function normalize(array $data): Data
    {
        return ImportCollectionData::from($data);
    }
}
