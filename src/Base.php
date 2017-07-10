<?php
namespace peang;

/**
 * @package base
 * @author  Irvan Setiawan
 * Base App for all request
 */
class Base
{
    /**
     * @var App
     */
    public static $app;

    /**
     * @var array
     */
    protected static $configs;

    /**
     * @return array
     */
    public static function getConfigs()
    {
        return self::$configs;
    }

    /**
     * @param array $configs
     */
    public static function setConfigs($configs)
    {
        self::$configs = $configs;
    }
}