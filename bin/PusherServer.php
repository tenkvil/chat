<?php

require_once __DIR__.'/../vendor/autoload.php';
use App\Chat\Helpers\SettingsHelper;

$zmqHost = SettingsHelper::getSetting('zeroMQ', 'host');
$zmqPort = SettingsHelper::getSetting('zeroMQ', 'port');
$pusherServerHost = SettingsHelper::getSetting('pusherServer', 'host');
$pusherServerPort = SettingsHelper::getSetting('pusherServer', 'port');

$loop   = React\EventLoop\Factory::create();
$pusher = new App\Ratchet\Pusher;

$context = new React\ZMQ\Context($loop);
$pull = $context->getSocket(ZMQ::SOCKET_PULL);
$pull->bind($zmqHost . ':' . $zmqPort);
$pull->on('message', array($pusher, 'onDataUpdate'));

$webSock = new React\Socket\Server($loop);
$webSock->listen($pusherServerPort, $pusherServerHost);
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
