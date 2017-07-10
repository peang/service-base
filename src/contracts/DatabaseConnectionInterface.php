<?php
namespace peang\contracts;

/**
 * Contracts for adapt database conenction provided
 * @package peang\contracts
 * @author  Irvan Setiawan
 */
interface DatabaseConnectionInterface
{
    /**
     * Connect given config
     * @return \PDO
     */
    public function connect();
}