<?php
namespace App\Chat\Helpers;

/**
 * Class Settings
 */
class SettingsHelper
{
    /**
     * @var string
     */
    private static $pathToConfig = __DIR__ . '/../../../Config/Config.php';

    /**
     * @param String $type
     * @param String $keyName
     * @return string
     */
    public static function getSetting(String $type, String $keyName) {
        $settings = self::getConfigData();
        return $settings[$type][$keyName];
    }

    /**
     * @return mixed
     */
    private static function getConfigData()
    {
        return include self::$pathToConfig;
    }
}
