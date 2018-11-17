<?php

namespace peang\rest;

use MongoDB\Client;
use MongoDB\Collection;
use peang\abstraction\DatabaseConnection;

/**
 * Class MongoModel
 * @author  Irvan Setiawan <peang.cookie@gmail.com>
 */
abstract class MongoModel
{
    /**
     * @var string
     */
    public $databaseName;

    /**
     * @var string
     */
    public $collectionName;

    /**
     * @var Client
     */
    protected $connection;

    /**
     * Model constructor.
     */
    public function __construct()
    {
        /** @var Client $connection */
        $connection = DatabaseConnection::getConnections()[$this->collectionName];

        $databaseName = $this->databaseName;
        $collectionName = $this->collectionName;

        $this->connection = $connection->$databaseName->$collectionName;
    }
}