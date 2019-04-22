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
    public static $db = [];

    /**
     * @var array
     */
    protected static $configs;

    /**
     * @var array
     */
    protected static $configsLocal;

    /**
     * @var array
     */
    protected static $paramsLocal;

    /**
     * @return array
     */
    public static function getConfigsLocal()
    {
        return self::$configsLocal;
    }

    /**
     * @param array $configs
     */
    public static function setConfigsLocal($configsLocal)
    {
        self::$configsLocal = $configsLocal;
    }

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

    /**
     * @return array
     */
    public static function getParamsLocal()
    {
        return self::$paramsLocal;
    }

    /**
     * @param array $configs
     */
    public static function setParamsLocal($paramsLocal)
    {
        self::$paramsLocal = $paramsLocal;
    }
}