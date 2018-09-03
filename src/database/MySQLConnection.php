<?php
namespace peang\database;

use peang\abstraction\DatabaseConnection;
use peang\contracts\DatabaseConnectionInterface;
use peang\helpers\Helpers;
use Illuminate\Container\Container;
use Illuminate\Database\ConnectionResolver;
use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @package base\database
 * @author  Irvan Setiawan <peang.cookie@gmail.com>
 */
class MySQLConnection extends DatabaseConnection implements DatabaseConnectionInterface
{
    /** @var string $host */
    private $host;

    /** @var string $user */
    private $user;

    /** @var string $user */
    private $pass;

    /** @var string $dbname */
    private $dbname;

    /**
     * MySQLConnection constructor.
     * @param $configs
     */
    public function __construct($configs)
    {
        $this->host   = Helpers::getValue($configs, 'host');
        $this->user   = Helpers::getValue($configs, 'user');
        $this->pass   = Helpers::getValue($configs, 'pass');
        $this->dbname = Helpers::getValue($configs, 'dbname');
    }

    /**
     * @return void
     */
    public function connect()
    {
        $settings = array(
            'driver'    => DatabaseConnection::MYSQL,
            'host'      => $this->host,
            'database'  => $this->dbname,
            'username'  => $this->user,
            'password'  => $this->pass,
            'collation' => 'utf8_general_ci',
            'charset'   => 'utf8',
            'prefix'    => ''
        );

        // Bootstrap Eloquent ORM
        $container      = new Container();
        $connFactory    = new ConnectionFactory($container);
        $conn           = $connFactory->make($settings);
        $resolver       = new ConnectionResolver();

        $resolver->addConnection('default', $conn);
        $resolver->setDefaultConnection('default');

        Model::setConnectionResolver($resolver);
    }
}
