<?php

declare(strict_types=1);

namespace App\Modules\Delivery\UseCase;

use App\Modules\Delivery\Models\Carrier;
use App\Modules\Delivery\Models\Pvz;
use App\Modules\Delivery\Services\CarrierService;
use App\Modules\Delivery\Services\MetroService;
use App\Modules\Delivery\Services\PvzCacheService;
use App\Modules\Delivery\Services\PvzImportService;
use App\Modules\Delivery\Services\PvzService;
use App\Modules\Delivery\Support\Address;
use App\Modules\Delivery\Support\Location;
use App\Packages\DataObjects\Delivery\ImportPvzData;
use App\Packages\DataObjects\Delivery\MetroData;
use Illuminate\Support\Collection;
use Psr\Log\LoggerInterface;

class ImportPvz
{
    public function __construct(
        private readonly PvzImportService $pvzImportService,
        private readonly CarrierService $carrierService,
        private readonly PvzService $pvzService,
        private readonly LoggerInterface $logger,
        private readonly MetroService $metroService,
        private readonly PvzCacheService $pvzCacheService
    ) {
    }

    public function __invoke(): void
    {
        $this->pvzImportService->import(function (ImportPvzData $data) {
            try {
                $pvz = $this->upsertPvz($data);

                if ($pvz !== null) {
                    $this->logger->info(
                        "[ImportPvz] Successfully imported PVZ with extID: $data->external_id. ID: $pvz->id"
                    );
                } else {
                    $this->logger->info("[ImportPvz] Deleted PVZ with extID: $data->external_id");
                }

                $this->pvzCacheService->warm($data->city, $this->pvzService->get($data->city));
            } catch (\Throwable $e) {
                $this->logger->error(
                    "[ImportPvz] Failed to import PVZ with extID: $data->external_id",
                    ['exception', $e]
                );
            }
        });
    }

    private function upsertPvz(ImportPvzData $importPvzData): ?Pvz
    {
        $pvz = $this->pvzService->getByExternalId($importPvzData->external_id);

        if ($importPvzData->delete === true && $pvz !== null) {
            $this->pvzService->delete($pvz);
            return null;
        }

        $carrier = $this->upsertCarrier($importPvzData);
        $metro = $this->upsertMetro($importPvzData->metro);

        $location = $this->createLocation($importPvzData);
        $address = $this->createAddress($importPvzData);

        if ($pvz === null) {
            $pvz = $this->pvzService->create(
                $location,
                $address,
                $importPvzData->external_id,
                $carrier,
                $importPvzData->work_time,
                $importPvzData->price,
                $metro
            );
        } else {
            $pvz = $this->pvzService->update(
                $pvz,
                $location,
                $address,
                $importPvzData->external_id,
                $importPvzData->work_time,
                $importPvzData->price,
                $metro
            );
        }

        return $pvz;
    }

    private function upsertCarrier(ImportPvzData $importPvzData): Carrier
    {
        return $this->carrierService->upsert(
            $importPvzData->carrier_name,
            $importPvzData->carrier_external_id
        );
    }

    /**
     * @param Collection<MetroData> $metro
     */
    private function upsertMetro(Collection $metro): Collection
    {
        return $metro->map(function (MetroData $metroData) {
            return $this->metroService->upsert(
                $metroData->name,
                $metroData->line
            );
        });
    }

    private function createLocation(ImportPvzData $importPvzData): Location
    {
        return new Location(
            $importPvzData->latitude,
            $importPvzData->longitude
        );
    }

    private function createAddress(ImportPvzData $importPvzData): Address
    {
        return new Address(
            $importPvzData->area,
            $importPvzData->city,
            $importPvzData->district,
            $importPvzData->street,
            $importPvzData->address
        );
    }
}
