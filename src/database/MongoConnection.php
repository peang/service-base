<?php

namespace peang\database;

use MongoDB\Client;
use peang\abstraction\DatabaseConnection;
use peang\contracts\DatabaseConnectionInterface;
use peang\helpers\Helpers;

/**
 * Class MongoConnection
 * @author  Irvan Setiawan <peang.cookie@gmail.com>
 */
class MongoConnection extends DatabaseConnection implements DatabaseConnectionInterface
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
     * @return Client|\PDO
     */
    public function connect()
    {
        if ($this->user && $this->pass) {
            $connectionString = sprintf('mongodb://%s:%s@%s:%s', $this->user, $this->pass, $this->host, $this->port);
        } else {
            $connectionString = sprintf('mongodb://%s:%s', $this->host, $this->port);
        }

        return new Client($connectionString);
    }
}