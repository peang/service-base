<?php
namespace base\validators;

use Respect\Validation\Exceptions\ValidationException;

/**
 * @package base\validators
 * @author  Irvan Setiawan <irvan.setiawan@tafern.com>
 */
class IsOneOfArrayException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} is not valid provided data.',
        ]
    ];
}