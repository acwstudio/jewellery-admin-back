<?php

declare(strict_types=1);

namespace App\Modules\XmlFeed\Contracts;

use DOMDocument;
use Illuminate\Support\Collection;

interface XmlFeedContract
{
    public function getDocument(Collection $products, ?callable $onEach = null): DOMDocument;
}
