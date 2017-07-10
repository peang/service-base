<?php
namespace base\validators;

use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Rules\AbstractRule;

/**
 * @package base\validators
 * @author  Irvan Setiawan <irvan.setiawan@tafern.com>
 */
class IsOneOfArray extends AbstractRule
{
    /**
     * @var array
     */
    private $arrayData;

    /**
     * IsOneOfArray constructor.
     *
     * @param $arrayData
     *
     * @throws ComponentException
     */
    public function __construct($arrayData)
    {
        if (!is_array($arrayData)) {
            throw new ComponentException('Data is not array.');
        }

        $this->arrayData = $arrayData;
    }

    /**
     * @param $input
     *
     * @return bool
     */
    public function validate($input)
    {
        if (!array_key_exists($input, $this->arrayData)) {
            return false;
        }

        return true;
    }
}