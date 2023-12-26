<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Laravel\Telescope\Telescope;

class Job implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected function withoutTelescope(callable $callback): void
    {
        if (method_exists(Telescope::class, 'withoutRecording')) {
            Telescope::withoutRecording($callback);
        } else {
            call_user_func($callback);
        }
    }
}
