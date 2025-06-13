<?php

namespace App\Listeners;

use App\Events\BookingCreatedEvent;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class SendBookingToRabbitMQ
{
    public function handle(BookingCreatedEvent $event)
    {
        $data = json_encode($event->booking);

        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('booking_created', false, true, false, false);

        $msg = new AMQPMessage($data, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
        $channel->basic_publish($msg, '', 'booking_created');

        Log::info('Booking event sent to RabbitMQ', ['queue' => 'booking_created']);

        $channel->close();
        $connection->close();
    }
}

