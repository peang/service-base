<?php
namespace peang\abstraction;

use peang\Base;
use peang\contracts\DatabaseConnectionInterface;
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

    /** @var array $dbDriver */
    private static $dbDriver = [
        'mysql'
    ];

    /** @var \PDO $connection */
    public static $connection = null;

    /**
     * @return void
     */
    public static function connectDb()
    {
        /** @var DatabaseConnectionInterface $connectionClass */
        $connectionClass = self::getConnectionDb();

        self::$connection = $connectionClass->connect();
    }

    /**
     * @return DatabaseConnectionInterface
     */
    private static function getConnectionDb()
    {
        /** @var ContainerInterface $container */
        $container = Base::$app->getContainer();

        $dbConfigs = Helpers::getValue($container, 'settings')['db'];

        $connectionDriver = Helpers::getValue($dbConfigs, 'driver');

        $configs = [
            'host'   => Helpers::getValue($dbConfigs, 'host'),
            'user'   => Helpers::getValue($dbConfigs, 'user'),
            'pass'   => Helpers::getValue($dbConfigs, 'pass'),
            'dbname' => Helpers::getValue($dbConfigs, 'dbname')
        ];

        if (!in_array($connectionDriver, self::$dbDriver)) {
            throw new \PDOException("unknown database driver");
        }

        switch ($connectionDriver) {
            case self::MYSQL:
                $connectionClass = new MysqlConnection($configs);
                break;
            default:
                throw new \PDOException("unknown database driver");
        }

        return $connectionClass;
    }
}
