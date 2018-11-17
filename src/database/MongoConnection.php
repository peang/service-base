<?php

namespace peang\database;

use peang\abstraction\DatabaseConnection;
use peang\contracts\DatabaseConnectionInterface;

/**
 * Class MongoConnection
 * @author  Irvan Setiawan <peang.cookie@gmail.com>
 */
class MongoConnection extends DatabaseConnection implements DatabaseConnectionInterface
{
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
     * Connect given config
     */
    public function connect()
    {
        $connectionString = sprintf('mongodb://%s:%s@%s:%s/%s', $this->user, $this->pass, $this->host, $this->port, $this->dbname);
        $connection = new \MongoClient($connectionString);

        $connection->connect();

        return $connection;
    }
}