<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Support\Pvz\DataNormalizer;

use App\Packages\DataObjects\Delivery\ImportPvzData;
use App\Packages\DataObjects\Delivery\MetroData;
use Illuminate\Support\Collection;
use Money\Money;

class Enterprise1CPvzDataNormalizer implements PvzDataNormalizerInterface
{
    public function normalize(array $rawData): ImportPvzData
    {
        $geo = $rawData['geo'][0];
        $address = $rawData['address'];

        return new ImportPvzData(
            $rawData['id_pvz'],
            $rawData['name'],
            $rawData['ID'],
            strval($geo['lat']),
            strval($geo['lon']),
            $rawData['workTime'],
            $this->getByKey($address, 'region'),
            $this->getCity($address),
            $this->getByKey($address, 'city_district'),
            $this->getByKey($address, 'street_with_type'),
            $this->getByKey($address, 'result'),
            Money::RUB($rawData['cost'] * 100),
            $this->getMetro($address),
            filter_var($rawData['del'], FILTER_VALIDATE_BOOLEAN)
        );
    }

    private function getByKey(array $data, string $key, $default = '')
    {
        foreach ($data as $row) {
            if (!empty($row[$key])) {
                return $row[$key];
            }
        }

        return $default;
    }

    private function getCity(array $data): string
    {
        $city = $this->getByKey($data, 'city');

        if (empty($city)) {
            $city = $this->getByKey($data, 'region');
        }

        return $city;
    }

    /**
     * @return Collection<MetroData>
     */
    private function getMetro(array $data): Collection
    {
        $metroRows = collect($this->getByKey($data, 'metro', []));
        return $metroRows->map(function (array $metro) {
            return new MetroData(
                $metro['name'],
                $metro['line']
            );
        });
    }
}
