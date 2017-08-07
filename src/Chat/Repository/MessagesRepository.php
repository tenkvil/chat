<?php
namespace App\Chat\Repository;

use App\Chat\Model\Message;
use App\DB\DB;

/**
 * Class MessagesRepository
 */
class MessagesRepository
{
    /**
     * @var \PDO
     */
    private $connection;

    /**
     * MessagesRepository constructor.
     */
    public function __construct()
    {
        $this->connection = DB::connect();
    }

    /**
     * @return Message[]
     */
    public function getAll()
    {
        $statement = $this->connection->query('SELECT * FROM messages ORDER BY `date`');

        return $statement->fetchAll(\PDO::FETCH_CLASS, Message::class);
    }

    /**
     * @param integer $id
     * @return Message
     */
    public function getById($id)
    {
        $statement = $this->connection->prepare ('SELECT * FROM messages WHERE `id` = :id');
        $statement->bindParam(':id', $id);
        $statement->execute();

        $statement->setFetchMode(\PDO::FETCH_CLASS, Message::class);

        return $statement->fetch();
    }

    /**
     * @param string $userName
     * @param string $text
     * @param string $token
     */
    public function addMessage($userName, $text, $token)
    {
        $statement = $this->connection->prepare (
            'INSERT INTO messages (`userName`, `text`, `token`, `date`) 
                      VALUES (:userName, :text, :token, Now())');

        $statement->bindParam(':userName', $userName);
        $statement->bindParam(':text', $text);
        $statement->bindParam(':token', $token);
        $statement->execute();
    }

    /**
     * @param $id
     * @param $token
     */
    public function deleteMessage($id, $token)
    {
        $chat = $this->getById($id);

        if ($chat->getToken() != $token) {
            return;
        }

        $statement = $this->connection->prepare ('DELETE FROM messages WHERE `id` = :id');
        $statement->bindParam(':id', $id);
        $statement->execute();
    }

    /**
     * @param integer $id
     */
    public function likeMessage($id)
    {
        $statement = $this->connection->prepare (
            'UPDATE messages SET `likeCount` = `likeCount` + 1 WHERE `id` = :id');
        $statement->bindParam(':id', $id);
        $statement->execute();
    }

    public function changeNameByToken($userName, $token)
    {
        $statement = $this->connection->prepare (
            'UPDATE messages SET `userName` = :userName WHERE `token` = :token');
        $statement->bindParam(':userName', $userName);
        $statement->bindParam(':token', $token);
        $statement->execute();
    }
}
