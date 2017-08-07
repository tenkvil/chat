<?php
namespace App\DB;

use App\Chat\Model\User;

class DB {
    private const HOST = 'localhost';
    private const DB   = 'chat';
    private const USER = 'root';
    private const PASSWORD = '';
    private const CHARSET = 'utf8';

    /**
     * @var \PDO
     */
    private static $pdo;

    /**
     * @return \PDO
     */
    public static function connect() {
        if (!self::$pdo) {
            $dsn = sprintf("mysql:host=%s;dbname=%s;charset=%s",
                self::HOST,
                self::DB,
                self::CHARSET
            );

            $opt = [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_CLASS,
                \PDO::ATTR_EMULATE_PREPARES => false,
            ];


            self::$pdo = new \PDO($dsn, self::USER, self::PASSWORD, $opt);
        }

        return self::$pdo;
    }
}
