<?php
namespace base\traits;

/**
 * Internal Class for validate identity
 * @author  Irvan Setiawan <peang.cookie@gmail.com>
 * @package base\traits
 */
trait IdentityAuthenticationTrait
{
    /**
     * Validates current password with hashed password
     *
     * @param $password
     * @param $password_hash
     * @return bool
     */
    public function validatePassword($password, $password_hash)
    {
        if (password_verify($password, $password_hash)) {
            return true;
        }

        return false;
    }
}