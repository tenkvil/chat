<?php
namespace App\Chat\Helpers;

/**
 * Class TokenHelper
 */
class TokenHelper
{
    public static function createUserToken()
    {
        if (!isset($_COOKIE['user_token'])) {
            setcookie('user_token', self::createToken());
        }
    }

    /**
     * @return string
     */
    private static function createToken()
    {
        return md5(time());
    }
}
