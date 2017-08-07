<?php

require_once __DIR__.'/../vendor/autoload.php';

$loop   = React\EventLoop\Factory::create();
$pusher = new App\Ratchet\Pusher;

$context = new React\ZMQ\Context($loop);
$pull = $context->getSocket(ZMQ::SOCKET_PULL);
$pull->bind('tcp://127.0.0.1:5555');
$pull->on('message', array($pusher, 'onDataUpdate'));


$webSock = new React\Socket\Server($loop);
$webSock->listen(1234, '0.0.0.0');
$webServer = new Ratchet\Server\IoServer(
    new Ratchet\Http\HttpServer(
        new Ratchet\WebSocket\WsServer(
            new Ratchet\Wamp\WampServer(
                $pusher
            )
        )
    ),
    $webSock
);

$loop->run();
