<?php

namespace peang\database;

use peang\abstraction\DatabaseConnection;
use peang\contracts\DatabaseConnectionInterface;
use peang\helpers\Helpers;
use Predis\Client;

/**
 * Class RedisConnection
 * @author  Irvan Setiawan <peang.cookie@gmail.com>
 */
class RedisConnection extends DatabaseConnection implements DatabaseConnectionInterface
{
    /** @var string $name */
    private $name;

    /** @var string $host */
    private $host;

    /** @var string $port */
    private $port;

    /**
     * MySQLConnection constructor.
     * @param $name
     * @param $configs
     * @throws \HttpInvalidParamException
     */
    public function __construct($name, $configs)
    {
        $this->name = $name;
        $this->host = Helpers::getValue($configs, 'host');
        $this->port = Helpers::getValue($configs, 'port');
    }

    /**
     * Connect given config
     * @return Client
     */
    public function connect()
    {
        return new Client([
            'scheme' => 'tcp',
            'host' => $this->host,
            'port' => $this->port
        ]);
    }
}