<?php

declare(strict_types=1);

namespace App\Modules\Promotions\UseCases;

use App\Modules\Promotions\Services\ImportPromotionService;
use App\Modules\Promotions\Services\PromotionService;
use App\Packages\DataObjects\Promotions\CreatePromotion;
use App\Packages\DataObjects\Promotions\UpdatePromotion;
use App\Packages\Exceptions\Promotions\CreatePromotionException;
use Illuminate\Support\Facades\DB;
use Psr\Log\LoggerInterface;

class ImportPromotions
{
    public function __construct(
        private readonly ImportPromotionService $importPromotionService,
        private readonly PromotionService $promotionService,
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(): void
    {
        try {
            $this->importPromotionService->import(function (CreatePromotion $data) {
                $this->upsert($data);
            });
        } catch (\Throwable $e) {
            $this->logger->emergency('[Promotions] Failed to import promotions. Service shutdown.', [
                'exception' => $e
            ]);
        }
    }

    private function upsert(CreatePromotion $data): void
    {
        DB::transaction(function () use ($data) {
            $promotion = $this->promotionService->getByExternalId($data->promotion->externalId);

            if ($promotion === null) {
                $promotion = $this->promotionService->create($data);
            } else {
                $this->promotionService->update($promotion, new UpdatePromotion(
                    $data->promotion->externalId,
                    $data->promotion->description,
                    $data->promotion->isActive
                ));
            }

            $this->logger->info('[Promotions] Success. Imported promotion', [
                'ID' => $promotion->id,
                'extId' => $promotion->external_id
            ]);
        });
    }
}
