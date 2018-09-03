<?php

namespace peang\exceptions;

/**
 * @package base\exceptions
 * @author  Irvan Setiawan <peang.cookie@gmail.com>
 */
class EntityAlreadyExistException extends \Exception
{
    /**
     * EntityAlreadyExistException constructor.
     *
     * @param string $entityName
     * @param string $propertyName
     * @param string $propertyValue
     */
    public function __construct($entityName, $propertyName, $propertyValue)
    {
        parent::__construct(sprintf("%s with %s '%s' already exist.", $entityName, $propertyName, $propertyValue), 400);
    }
}
