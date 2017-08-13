<?php
require_once __DIR__.'/../vendor/autoload.php';
use App\Chat\Helpers\SettingsHelper;

$host = SettingsHelper::getSetting('database', 'host');
$user = SettingsHelper::getSetting('database', 'user');
$pass = SettingsHelper::getSetting('database', 'pass');
$db = SettingsHelper::getSetting('database', 'db');


$conn = new PDO("mysql:host=".$host, $user, $pass);

$conn->exec("CREATE DATABASE IF NOT EXISTS `".$db."` DEFAULT CHARACTER SET `utf8`");

$conn->exec("USE `" . $db . "`");

$conn->exec("
     CREATE TABLE `messages` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `text` text,
      `date` datetime DEFAULT NULL,
      `userName` varchar(45) DEFAULT NULL,
      `token` varchar(45) NOT NULL,
      `likeCount` int(11) DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8
");
