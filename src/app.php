<?php
use Symfony\Component\Routing;
use App\Chat\Controller\ChatController;

$routes = new Routing\RouteCollection();

$routes->add('index', new Routing\Route('/', [
    '_controller' => [ new ChatController(), 'indexAction' ]
]));

$routes->add('getAllChats', new Routing\Route('/getAllChats', [
    '_controller' => [ new ChatController(), 'getAllChatsAction' ]
]));

$routes->add('newMessage', new Routing\Route('/newMessage', [
    '_controller' => [ new ChatController(), 'newMessageAction' ]
]));

$routes->add('removeMessage', new Routing\Route('/removeMessage', [
    '_controller' => [ new ChatController(), 'removeMessageAction' ]
]));

$routes->add('likeMessage', new Routing\Route('/likeMessage', [
    '_controller' => [ new ChatController(), 'likeMessageAction' ]
]));

$routes->add('changeName', new Routing\Route('/changeName', [
    '_controller' => [ new ChatController(), 'changeNameAction' ]
]));

return $routes;
