<?php

namespace peang\abstraction;

use peang\Base;
use peang\contracts\DatabaseConnectionInterface;
use peang\database\MongoConnection;
use peang\database\MySQLConnection as MysqlConnection;
use peang\helpers\Helpers;
use Interop\Container\ContainerInterface;

/**
 * Connect database
 * @package base\database
 * @author  Irvan Setiawan <peang.cookie@gmail.com>
 */
abstract class DatabaseConnection
{
    const MYSQL = 'mysql';
    const MONGO = 'mongo';
    const REDIS = 'redis';

    /**
     * @var array
     */
    private static $connections = [];

    /**
     * @return void
     * @throws \Exception
     */
    public static function connectDb()
    {
        /** @var DatabaseConnectionInterface $connectionClass */
        self::getConnectionDb();
    }

    /**
     * @return DatabaseConnectionInterface
     * @throws \HttpInvalidParamException
     */
    private static function getConnectionDb()
    {
        /** @var ContainerInterface $container */
        $container = Base::$app->getContainer();

        $databases = Helpers::getValue($container, 'settings')['db'];

        foreach ($databases as $name => $configs) {
            $connectionDriver = Helpers::getValue($configs, 'driver');
            unset($configs['driver']);

            switch ($connectionDriver) {
                case self::MYSQL:
                    $connectionClass = new MysqlConnection($name, $configs);
                    break;
                case self::MONGO:
                    $connectionClass = new MongoConnection($name, $configs);
                    break;
                case self::MONGO:
                    $connectionClass = new MongoConnection($name, $configs);
                    break;
                default:
                    throw new \Exception("Unknown Database Driver");
            }

            self::$connections[$name] = $connectionClass->connect();
        }
    }

    /**
     * @return array
     */
    public static function getConnections() {
        return self::$connections;
    }
}
