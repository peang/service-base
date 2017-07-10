<?php
namespace peang\base\contracts;

/**
 * Contracts for all authentication adapter
 *
 * @package base\contracts
 * @author  Irvan Setiawan <irvan.setiawan@at.co.id>
 */
interface AuthenticationInterface
{
    /**
     * This will generate token required for authentication process
     * Will return string JWT Token
     * @return string
     */
    public static function generateToken();

    /**
     * This will validate every request token before returning any API's value
     * @return void
     */
    public function validate();
}