<?php
namespace App\Chat\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Chat\Repository\MessagesRepository;
use App\Chat\Model\Message;

/**
 * Class chatController
 */
class ChatController
{
    /**
     * @return Response
     */
    public function indexAction() : Response
    {
        return new Response($this->renderIndex());
    }

    /**
     * @return Response
     */
    public function getAllChatsAction() : Response
    {
        $messagesRepository = new MessagesRepository();
        $messages= $messagesRepository->getAll();
        $prepareMessage = [];

        /** @var Message $message*/
        foreach ($messages as $message) {
            array_push($prepareMessage, [
                'id' => $message->getId(),
                'userName' => $message->getUserName(),
                'text' => $message->getText(),
                'date' => $message->getDate(),
                'likeCount' => $message->getLikeCount(),
                'token' => $message->getToken()
            ]);
        }

        $data = json_encode($prepareMessage);

        return new Response("{\"status\": \"Ok\", \"data\": $data}", 200);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function newMessageAction(Request $request) : Response
    {
        $data = $request->request->all();

        if (!$data['userName'] || !$data['text'] || !$data['token']) {
            return new Response("{\"status\": \"Error\"}", 400);
        }

        $messagesRepository = new MessagesRepository();
        $messagesRepository->addMessage($data['userName'], $data['text'], $data['token']);
        $this->sendEventToClient();

        return new Response("{\"status\": \"Ok\"}", 200);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function removeMessageAction(Request $request) : Response
    {
        $data = $request->request->all();

        if (!$data['id']) {
            return new Response("{\"status\": \"Error\"}", 400);
        }

        $messagesRepository = new MessagesRepository();
        $messagesRepository->deleteMessage($data['id'], $data['token']);
        $this->sendEventToClient();

        return new Response("{\"status\": \"Ok\"}", 200);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function likeMessageAction(Request $request) : Response
    {
        $data = $request->request->all();

        if (!$data['id']) {
            return new Response("{\"status\": \"Error\"}", 400);
        }

        $messagesRepository = new MessagesRepository();
        $messagesRepository->likeMessage($data['id']);
        $this->sendEventToClient();

        return new Response("{\"status\": \"Ok\"}", 200);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function changeNameAction (Request $request) : Response
    {
        $data = $request->request->all();

        if (!$data['userName'] || !$data['token']) {
            return new Response("{\"status\": \"Error\"}", 400);
        }

        $messagesRepository = new MessagesRepository();
        $messagesRepository->changeNameByToken($data['userName'], $data['token']);
        $this->sendEventToClient();

        return new Response("{\"status\": \"Ok\"}", 200);
    }

    /**
     * @return string
     */
    private function renderIndex()
    {
        ob_start();
        include __DIR__.'/../../../web/public/index.html';

        return ob_get_clean();
    }

    /**
     * Send event to zmq for broadcast data to all clients
     */
    private function sendEventToClient() {
        $entryData = [
            'eventType' => 'pubSub'
        ];

        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect("tcp://localhost:5555");

        $socket->send(json_encode($entryData));
    }
}
