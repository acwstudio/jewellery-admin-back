<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Traits;

trait ProductTrait
{
    public function updateInScout(): void
    {
        $this->refresh();
        $this->save();
    }
}
