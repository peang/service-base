<?php
namespace peang\rest;

use peang\exceptions\InvalidModelConfigurationException;
use peang\helpers\Helpers;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Ramsey\Uuid\Uuid;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator;
use Slim\Http\Request;

/**
 * Base Model to use for query
 * @package base\rest
 * @author  Irvan Setiawan <peang.cookie@gmail.com>
 */
abstract class Model extends EloquentModel
{
    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @var string
     */
    public $prefix;

    /**
     * Model constructor.
     */
    public function __construct()
    {
        $this->setTable($this->getTableNames());
        
        parent::__construct();
    }

    /**
     * @param $primaryKeyValue
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function findOne($primaryKeyValue)
    {
        $primaryKey = self::getPrimaryKey();
        $query = self::query();

        if (Helpers::isArrayAssociative($primaryKeyValue)) {
            foreach ($primaryKeyValue as $k => $v) {
                $query->orWhere($k, '=', $v)->first();
            }
        } else {
            $query->where($primaryKey, '=', $primaryKeyValue);
        }

        return $query->get()->first();
    }

    /**
     * @param $params
     *
     * @return object|static|null
     */
    public static function findOneBy($params)
    {
        $query = self::query();

        foreach ($params as $key => $value) {
            $query->where($key, '=', $value);
        }

        /** @var static $result */
        $result = $query->get()->first();

        if ($result) {
            return $result;
        }

        return $result;
    }

    /**
     * @return mixed
     */
    protected static function getPrimaryKey()
    {
        $classname = get_called_class();

        /** @var static $modelClass */
        $modelClass = new $classname();

        return $modelClass->primaryKey;
    }

    /**
     * @param $primaryKey
     */
    protected function setPrimaryKey($primaryKey)
    {
        $this->primaryKey = $primaryKey;
    }

    /**
     * @return bool
     */
    public function validate()
    {
        $modelRules = $this->getRules();

        $refl = new \ReflectionClass($this);
        $props = $refl->getProperties();
        /** @var \ReflectionProperty $prop */
        foreach ($props as $prop) {
            $validator = new Validator();
            $rules = Helpers::getValue($modelRules, $prop->getName());

            if ($rules) {
                foreach ($rules as $rule) {
                    $validator->addRule($rule);
                }

                try {
                    $validator->check($this->getAttribute($prop->getName()));
                } catch (ValidationException $e) {
                    $template = $e->getTemplate();
                    $params = $e->getParams();

                    $params['name'] = $prop->getName();

                    $this->errors[$prop->getName()] = ValidationException::format($template, $params);
                }
            }
        }

        if (!empty($this->errors)) {
            throw new ValidationException('Validation Exception.', 422);
        }

        return true;
    }

    /**
     * @param Request $request
     */
    public function loadAttributes(Request $request)
    {
        $parsedBody = $request->getParsedBody();

        /** @var \ReflectionClass $reflClass */
        $class = get_called_class();
        $reflClass = new \ReflectionClass(new $class());

        /** @var \ReflectionProperty $prop */
        foreach ($reflClass->getProperties() as $prop) {
            if ($prop->class == $reflClass->getName()) {
                $this->setAttribute($prop->getName(), Helpers::getValue($parsedBody, $prop->getName()));
            }
        }
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $options
     *
     * @return bool
     * @throws InvalidModelConfigurationException
     */
    public function save(array $options = [])
    {
        $pk = static::getPrimaryKey();
        $prefix = $this->prefix;

        if (!$prefix) {
            throw new InvalidModelConfigurationException("Model has no prefix for OID.");
        }

        if (strlen($prefix) > 4) {
            throw new InvalidModelConfigurationException("Maximum prefix length is 4 chars");
        }

        $oid = str_replace('-', '', Uuid::uuid4()->toString());
        $this->setAttribute($pk, strtoupper(sprintf('%s_%s', $prefix, $oid)));

        return parent::save($options);
    }

    /**
     * @return array
     */
    abstract public function getRules();

    /**
     * @return string
     */
    abstract public function getTableNames();
}