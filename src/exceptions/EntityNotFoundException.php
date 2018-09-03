<?php
namespace peang\exceptions;

/**
 * @package base\exceptions
 * @author  Irvan Setiawan <peang.cookie@gmail.com>
 */
class EntityNotFoundException extends \Exception
{
    /**
     * EntityNotFoundException constructor.
     *
     * @param string $entityName
     * @param int $propertyName
     * @param \Throwable $propertyValue
     */
    public function __construct($entityName, $propertyName, $propertyValue)
    {
        parent::__construct(sprintf("%s with %s '%s' not found.", $entityName, $propertyName, $propertyValue), 404);
    }
}
