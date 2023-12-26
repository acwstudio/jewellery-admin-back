<?php

declare(strict_types=1);

namespace App\Packages\ModuleClients;

use Illuminate\Support\Collection;

interface AnalyticsModuleClientInterface
{
    /**
     * @return Collection<string>
     */
    public function getGiftCardCycles(): Collection;

    /**
     * @param array|null $cycles
     * @param callable $callback
     */
    public function eachGiftCardChunk(?array $cycles, callable $callback): void;
}
