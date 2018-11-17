<?php

namespace peang\database;

use Illuminate\Container\Container;
use Illuminate\Database\ConnectionResolver;
use Illuminate\Database\Connectors\ConnectionFactory;
use peang\abstraction\DatabaseConnection;
use peang\contracts\DatabaseConnectionInterface;
use peang\helpers\Helpers;

/**
 * @package base\database
 * @author  Irvan Setiawan <peang.cookie@gmail.com>
 */
class MySQLConnection extends DatabaseConnection implements DatabaseConnectionInterface
{
    /** @var string $name */
    private $name;

    /** @var string $host */
    private $host;

    /** @var string $port */
    private $port;

    /** @var string $user */
    private $user;

    /** @var string $user */
    private $pass;

    /** @var string $dbname */
    private $dbname;

    /**
     * MySQLConnection constructor.
     * @param $configs
     * @throws \HttpInvalidParamException
     */
    public function __construct($name, $configs)
    {
        $this->name = $name;
        $this->host = Helpers::getValue($configs, 'host');
        $this->port = Helpers::getValue($configs, 'port');
        $this->user = Helpers::getValue($configs, 'user');
        $this->pass = Helpers::getValue($configs, 'pass');
        $this->dbname = Helpers::getValue($configs, 'dbname');
    }

    /**
     * @return ConnectionResolver
     */
    public function connect()
    {
        $settings = array(
            'driver' => DatabaseConnection::MYSQL,
            'host' => $this->host,
            'port' => $this->port,
            'database' => $this->dbname,
            'username' => $this->user,
            'password' => $this->pass,
            'collation' => 'utf8_general_ci',
            'charset' => 'utf8'
        );

        // Bootstrap Eloquent ORM
        $container = new Container();
        $connFactory = new ConnectionFactory($container);
        $conn = $connFactory->make($settings);
        $resolver = new ConnectionResolver();

        $resolver->addConnection($this->name, $conn);
        if ($this->name === 'default') {
            $resolver->setDefaultConnection('default');
        }

        return $resolver;
    }
}
