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

    public static function createSecret()
    {
        if(!isset($_SESSION)) {
            session_start();
        }

        if(!isset($_SESSION['secret']))
        {
            $_SESSION['secret'] = time();
        }
    }

    /**
     * @return string
     */
    public static function getCSRFToken()
    {
        $token = '';

        if(isset($_SESSION['secret']) && isset($_COOKIE['user_token'])) {
            $token = md5($_SESSION['secret'] . $_COOKIE['user_token']);
        }

        return $token;
    }

    public static function createCSRFToken()
    {
        setcookie('CSRF', self::getCSRFToken());
    }

    /**
     * @return string
     */
    private static function createToken()
    {
        return md5(time());
    }
}
