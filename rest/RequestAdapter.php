<?php
namespace peang\base\rest;

use Respect\Validation\Rules\Type;
use Respect\Validation\Validator;

/**
 * @package base\rest
 * @author  Irvan Setiawan <irvan.setiawan@tafern.com>
 */
abstract class RequestAdapter
{
    /**
     * @var array
     */
    protected $contentData;

    /**
     * @return array
     */
    abstract function getForm();

    public function validate()
    {
        $validator = new Validator();
        $forms = $this->getForm();

        foreach ($forms as $input => $rules) {
            /** @var Type $rule */
            foreach ($rules as $rule) {
                $validator::between(3, 10)->check($input);
            }

            $validator->check($input);
        }
        var_dump($forms);
        die;
    }
}