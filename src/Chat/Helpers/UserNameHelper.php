<?php
namespace App\Chat\Helpers;

/**
 * Class UserNameHelper
 */
class UserNameHelper
{
    public static function createUserName()
    {
        if (!isset($_COOKIE['user_name'])) {
            setcookie('user_name', self::createName());
        }
    }

    /**
     * @return string
     */
    private static function createName()
    {
        return 'user_' . substr(md5(time()),0,5);
    }
}
