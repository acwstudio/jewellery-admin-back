<?php

declare(strict_types=1);

namespace App\Packages\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Event;

class UpdateStatusCommandHasFailed extends Event
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * AcquiringPaymentWasCreated constructor.
     *
     * @param array $exceptions
     */
    public function __construct(
        private readonly array $exceptions
    ) {
    }

    public function getExceptions(): array
    {
        return $this->exceptions;
    }
}
