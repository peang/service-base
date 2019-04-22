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

    /** @var string $dsn */
    private $dsn;

    /** @var boolean $isSrv */
    private $isSrv = false;

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
        $this->dsn = Helpers::getValue($configs, 'dsn');
        $this->isSrv = Helpers::getValue($configs, 'isSrv');
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
        $prefix = 'mongodb';
        if ($this->isSrv) {
            $prefix = 'mongodb+srv';
        }

        if ($this->user && $this->pass) {
            $connectionString = sprintf('%s://%s:%s@%s:%s', $prefix, $this->user, $this->pass, $this->host, $this->port);
        } elseif ($this->dsn) {
            $connectionString = sprintf('%s://%s/%s?retryWrites=true', $prefix, $this->dsn, $this->dbname);
        } else {
            $connectionString = sprintf('%s://%s:%s', $prefix, $this->host, $this->port);
        }

//        var_dump($connectionString);
//        $client = new Client($connectionString);
//        var_dump($client->listDatabases());
//        die;
        return new Client($connectionString);
    }
}