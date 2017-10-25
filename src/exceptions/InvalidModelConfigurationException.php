<?php
namespace peang\exceptions;

use Throwable;

/**
 * @package peang\exceptions
 * @author  Irvan Setiawan <irvan.setiawan@tafern.com>
 */
class InvalidModelConfigurationException extends \Exception
{
    /**
     * InvalidModelConfigurationException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Invalid Model Configurations", $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}