<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

class DbExport extends Command
{
    protected $signature = "db:export {table} {queue} {--primary=} {--chunk=} {--except=*}";
    protected $description = "";

    public function __construct(
        private readonly LoggerInterface $logger
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        /** @var AbstractConnection $connection */
        $connection = App::make(AbstractConnection::class);
        $table = $this->argument('table');
        $queue = $this->argument('queue');

        try {
            $count = DB::table($table)->count();
            $this->logger->info("[DbExport] Preparing to export $count rows from '$table' table");
        } catch (\Throwable $e) {
            $this->logger->error("[DbExport] Failed to fetch '$table' table rows count", [
                'exception' => $e
            ]);
            return;
        }

        $query = DB::table($table);

        $primary = $this->option('primary');
        if ($primary !== null) {
            $query->orderBy($primary);
        } else {
            $query->orderBy('id');
        }

        $query->chunk($this->option('chunk') ?? 500, function (Collection $rows) use ($queue, $connection) {
            $count = $rows->count();
            try {
                $channel = $connection->channel();
                $channel->queue_declare($queue, durable: true, auto_delete: false);

                $message = new AMQPMessage($rows->map(function ($row) {
                    $row = (collect($row));
                    $except = $this->option('except');

                    if ($except) {
                        $row = $row->except($except);
                    }

                    return $row->toArray();
                })->toJson());

                $channel->basic_publish($message, '', $queue);
                $channel->close();
                $this->logger->info("[DbExport] Exported $count rows to '$queue' queue");
            } catch (\Throwable $e) {
                $this->logger->error("[DbExport] Failed to export $count rows to '$queue' queue", [
                    'exception' => $e,
                ]);
            }
        });

        $connection->close();
    }
}
