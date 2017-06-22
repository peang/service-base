<?php
namespace base\contracts;

/**
 * Contracts for adapt database conenction provided
 * @package base\contracts
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