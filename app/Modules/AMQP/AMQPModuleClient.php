<?php

declare(strict_types=1);

namespace App\Modules\AMQP;

use App\Packages\ModuleClients\AMQPModuleClientInterface;
use Illuminate\Support\Facades\App;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

final class AMQPModuleClient implements AMQPModuleClientInterface
{
    private AbstractConnection $connection;

    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    public function consume(string $queue, \Closure $callback): void
    {
        $this->connection = App::make(AbstractConnection::class);

        $channel = $this->prepareConsume($queue, function (AMQPMessage $message) use ($queue, $callback) {
            $this->logger->info("[{$queue}] Received message", [
                'body' => $message->body
            ]);
            $message->ack();
            return $callback(json_decode($message->body, true));
        });

        while ($channel->is_open()) {
            $channel->wait();
        }

        $this->close($channel);
    }

    public function publish(string $queue, $message): void
    {
        $this->connection = App::make(AbstractConnection::class);

        if (!$message instanceof AMQPMessage) {
            $message = $this->createMessage($message);
        }

        $channel = $this->send($queue, $message);
        $this->close($channel);
        $this->logger->info("[{$queue}] Published message", [
            'body' => $message->body
        ]);
    }

    private function prepareConsume(string $queue, \Closure $callback): AMQPChannel
    {
        $channel = $this->connection->channel();
        $channel->queue_declare($queue, durable: true, auto_delete: false);
        $channel->basic_consume($queue, callback: $callback);
        return $channel;
    }

    private function send(string $queue, AMQPMessage $message): AMQPChannel
    {
        $channel = $this->connection->channel();
        $channel->queue_declare($queue, durable: true, auto_delete: false);
        $channel->basic_publish($message, '', $queue);
        return $channel;
    }

    private function close(AMQPChannel $channel): void
    {
        $channel->close();
        $this->connection->close();
    }

    private function createMessage($message): AMQPMessage
    {
        if (is_array($message)) {
            $message = json_encode($message);
        } elseif (!is_string($message)) {
            $message = strval($message);
        }

        return new AMQPMessage($message);
    }
}
