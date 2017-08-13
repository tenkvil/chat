<?php
namespace App\DB;

use App\Chat\Helpers\SettingsHelper;

/**
 * Class DB
 */
class DB {
    /**
     * @var \PDO
     */
    private static $pdo;

    /**
     * @return \PDO
     */
    public static function connect() {
        if (!self::$pdo) {
            $host = SettingsHelper::getSetting('database', 'host');
            $user = SettingsHelper::getSetting('database', 'user');
            $pass = SettingsHelper::getSetting('database', 'pass');
            $charset = SettingsHelper::getSetting('database', 'charset');
            $db = SettingsHelper::getSetting('database', 'db');

            $dsn = sprintf("mysql:host=%s;dbname=%s;charset=%s",
                $host,
                $db,
                $charset
            );

            $opt = [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_CLASS,
                \PDO::ATTR_EMULATE_PREPARES => false,
            ];

            self::$pdo = new \PDO($dsn, $user, $pass, $opt);
        }

        return self::$pdo;
    }
}
