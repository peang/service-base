<?php

namespace peang\abstraction;

use Illuminate\Database\ConnectionResolver;
use peang\Base;
use peang\contracts\DatabaseConnectionInterface;
use peang\database\MongoConnection;
use peang\database\MySQLConnection as MysqlConnection;
use peang\database\RedisConnection;
use peang\helpers\Helpers;
use Interop\Container\ContainerInterface;
use Predis\Client;

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
                    /** @var ConnectionResolver $connectionClass */
                    $connectionClass = new MysqlConnection($name, $configs);
                    break;
                case self::MONGO:
                    /** @var \MongoDB\Client $connectionClass */
                    $connectionClass = new MongoConnection($name, $configs);
                    break;
                case self::REDIS:
                    /** @var Client $connectionClass */
                    $connectionClass = new RedisConnection($name, $configs);
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

    /**
     * @param $connectionName
     * @return ConnectionResolver|\MongoDB\Client|Client
     * @throws \HttpInvalidParamException
     */
    public static function getConnection($connectionName) {
        $value = Helpers::getValue(self::getConnections(), $connectionName, null);
        if (!$value) {
            throw new \Exception('Cannot find connection with that name');
        }

        return $value;
    }
}
