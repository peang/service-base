<?php
namespace base\abstraction;

use base\App;
use base\contracts\DatabaseConnectionInterface;
use base\database\MySQLConnection as MysqlConnection;
use base\helpers\Helpers;
use Interop\Container\ContainerInterface;
use Symfony\Component\Console\Helper\Helper;

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
    public static $connection;

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
        $container = App::$app->getContainer();

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