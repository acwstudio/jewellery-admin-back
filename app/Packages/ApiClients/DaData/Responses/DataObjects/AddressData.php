<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\DaData\Responses\DataObjects;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class AddressData extends Data
{
    public function __construct(
        public readonly ?string $postal_code,
        public readonly string $country,
        public readonly string $country_iso_code,
        public readonly ?string $federal_district,

        public readonly ?string $region_fias_id,
        public readonly ?string $region_kladr_id,
        public readonly ?string $region_iso_code,
        public readonly ?string $region_with_type,
        public readonly ?string $region_type,
        public readonly ?string $region_type_full,
        public readonly ?string $region,

        public readonly ?string $area_fias_id,
        public readonly ?string $area_kladr_id,
        public readonly ?string $area_with_type,
        public readonly ?string $area_type,
        public readonly ?string $area_type_full,
        public readonly ?string $area,

        public readonly ?string $city_fias_id,
        public readonly ?string $city_kladr_id,
        public readonly ?string $city_with_type,
        public readonly ?string $city_type,
        public readonly ?string $city_type_full,
        public readonly ?string $city,
        public readonly ?string $city_area,

        public readonly ?string $city_district_fias_id,
        public readonly ?string $city_district_kladr_id,
        public readonly ?string $city_district_with_type,
        public readonly ?string $city_district_type,
        public readonly ?string $city_district_type_full,
        public readonly ?string $city_district,

        public readonly ?string $settlement_fias_id,
        public readonly ?string $settlement_kladr_id,
        public readonly ?string $settlement_with_type,
        public readonly ?string $settlement_type,
        public readonly ?string $settlement_type_full,
        public readonly ?string $settlement,

        public readonly ?string $street_fias_id,
        public readonly ?string $street_kladr_id,
        public readonly ?string $street_with_type,
        public readonly ?string $street_type,
        public readonly ?string $street_type_full,
        public readonly ?string $street,

        public readonly ?string $stead_fias_id,
        public readonly ?string $stead_cadnum,
        public readonly ?string $stead_type,
        public readonly ?string $stead_type_full,
        public readonly ?string $stead,

        public readonly ?string $house_fias_id,
        public readonly ?string $house_kladr_id,
        public readonly ?string $house_cadnum,
        public readonly ?string $house_type,
        public readonly ?string $house_type_full,
        public readonly ?string $house,

        public readonly ?string $block_type,
        public readonly ?string $block_type_full,
        public readonly ?string $block,
        public readonly ?string $entrance,
        public readonly ?string $floor,

        public readonly ?string $flat_fias_id,
        public readonly ?string $flat_cadnum,
        public readonly ?string $flat_type,
        public readonly ?string $flat_type_full,
        public readonly ?string $flat,
        public readonly ?string $flat_area,

        public readonly ?string $square_meter_price,
        public readonly ?string $flat_price,
        public readonly ?string $postal_box,
        public readonly ?string $fias_id,
        public readonly ?string $fias_code,
        public readonly ?string $fias_level,

        public readonly ?string $fias_actuality_state,
        public readonly ?string $kladr_id,
        public readonly ?string $geoname_id,
        public readonly ?string $capital_marker,

        public readonly ?string $okato,
        public readonly ?string $oktmo,
        public readonly ?string $tax_office,
        public readonly ?string $tax_office_legal,
        public readonly ?string $timezone,
        public readonly ?string $geo_lat,
        public readonly ?string $geo_lon,
        public readonly ?string $beltway_hit,
        public readonly ?string $beltway_distance,
        #[DataCollectionOf(MetroData::class)]
        public readonly ?DataCollection $metro,
        public readonly ?array $history_values
    ) {
    }
}
