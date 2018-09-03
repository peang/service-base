<?php
namespace peang\validators;

use Respect\Validation\Exceptions\ValidationException;

/**
 * @package base\validators
 * @author  Irvan Setiawan <peang.cookie@gmail.com>
 */
class IsOneOfArrayException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} is not valid provided data.',
        ]
    ];
}
