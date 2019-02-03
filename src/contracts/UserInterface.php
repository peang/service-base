<?php
namespace peang\contracts;

/**
 * Contracts for default user data
 * @package peang\contracts
 * @author  Irvan Setiawan
 *
 * @property string|int $id
 * @property string|int $role_id
 */
interface UserInterface
{
    /**
     * @return string|int
     */
    public function getId();

    /**
     * @return int
     */
    public function getRoleId();
}
