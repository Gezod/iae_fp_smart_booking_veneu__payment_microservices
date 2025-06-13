<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use App\Events\BookingCreatedEvent;

require __DIR__ . '/vendor/autoload.php';

// Inisialisasi Laravel Application supaya bisa pakai Event dan Model
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('booking_created', false, true, false, false);

echo "[*] Waiting for messages from RabbitMQ queue 'booking_created'. To exit press CTRL+C\n";

$callback = function (AMQPMessage $msg) {
    $data = json_decode($msg->getBody(), true);
    event(new BookingCreatedEvent($data));
    echo "[✓] Event dispatched with data:\n";
    print_r($data);
};

$channel->basic_consume('booking_created', '', false, true, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}
