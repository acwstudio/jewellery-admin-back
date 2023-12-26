<?php

namespace App\Console;

use Illuminate\Console\Command as BaseCommand;
use Laravel\Telescope\Telescope;
use Symfony\Component\Console\Helper\ProgressBar;

class Command extends BaseCommand
{
    protected function initializeProgressBar(): ProgressBar
    {
        $bar = $this->output->createProgressBar();
        $bar->setFormat('debug');

        return $bar;
    }

    protected function withoutTelescope(callable $callback): void
    {
        if (method_exists(Telescope::class, 'withoutRecording')) {
            Telescope::withoutRecording($callback);
        } else {
            call_user_func($callback);
        }
    }
}
