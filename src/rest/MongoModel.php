<?php

namespace peang\rest;

use helpers\Filter;
use models\Role;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Regex;
use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\Model\BSONDocument;
use peang\abstraction\DatabaseConnection;
use peang\App;
use peang\Base;
use peang\helpers\Helpers;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * Class MongoModel
 * @author  Irvan Setiawan <peang.cookie@gmail.com>
 */
abstract class MongoModel
{
    /**
     * @var string
     */
    public $databaseName;

    /**
     * @var string
     */
    public $collectionName;

    /**
     * @var Client
     */
    protected $connection;

    /**
     * @var array
     */
    public $attributes = [];

    /**
     * @var array
     */
    protected $attributesValue = [];

    /**
     * @var array 
     */
    protected $errors = [];

    /**
     * Model constructor.
     */
    public function __construct()
    {
        /** @var Client $connection */
        $connection = DatabaseConnection::getConnections()['mongo'];
        if (!$connection) {
            throw new InvalidConfigurationException('Need to setup mongo in configs-local');
        }

        $databases = Base::$app->getContainer()->get('settings')->get('db');
        $this->databaseName = Helpers::getValue($databases, 'mongo.dbname', null);
        if (!$this->databaseName) {
            throw new InvalidConfigurationException('No database set for mongo');
        }

        if (!$this->collectionName) {
            throw new InvalidConfigurationException('No collection set found for mongo');
        }

        $databaseName = $this->databaseName;
        $collectionName = $this->collectionName;

        $this->connection = $connection->$databaseName->$collectionName;

        foreach($this->attributes as $attributeKey => $attributeName) {
            $this->attributesValue[$attributeName] = 'null';
        }
    }

    /**
     * @return array
     */
    public function getAttributesValue()
    {
        return $this->attributesValue;
    }

    /**
     * @param $attribute
     * @return mixed
     * @throws \HttpInvalidParamException
     */
    public function getAttributeValue($attribute)
    {
        return Helpers::getValue($this->attributesValue, $attribute, null);
    }

    /**
     * @param $attributes
     */
    public function setAttributesValue($attributes)
    {
        if ($attributes) {
            foreach ($attributes as $attributeName => $attributeValue) {
                switch ($attributeValue) {
                    case is_a($attributeValue, 'string'):
                        $this->attributesValue[$attributeName] = (string) $attributeValue;
                        break;
                    case is_a($attributeValue, BSONDocument::class):
                        $this->attributesValue[$attributeName] = (array) $attributeValue;
                        break;
                    case (!$attributeValue):
                        $this->attributesValue[$attributeName] = null;
                        break;
                    default:
                        $this->attributesValue[$attributeName] = $attributeValue;
                        break;
                }
            }
        }
        return $this;
    }

    /**
     * @param $attributeName
     * @param $attributeValue
     */
    public function setAttributeValue($attributeName, $attributeValue)
    {
        switch ($attributeValue) {
            case is_a($attributeValue, 'string'):
                $this->attributesValue[$attributeName] = (string) $attributeValue;
                break;
            case is_a($attributeValue, BSONDocument::class):
                $this->attributesValue[$attributeName] = (array) $attributeValue;
                break;
            case (!$attributeValue):
                $this->attributesValue[$attributeName] = null;
                break;
            default:
                $this->attributesValue[$attributeName] = $attributeValue;
                break;
        }
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
                    $validator->check($this->getAttributesValue()[$prop->getName()]);
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
     * @param int $page
     * @param int $perPage
     * @param null $sort
     * @param null $filters
     * @return array
     */
    public static function getList($page = 1, $perPage = 10, $sort = null, Filter $filters = null) {
        $self = new static();
        $skip = (int) ($perPage * ($page - 1));
        $limit = (int) $perPage;

        if ($sort) {
            if (substr($sort, 0, 1) === '-') {
                $sortString = substr($sort, 1, strlen($sort));
                $sort = [
                    $sortString => -1
                ];
            } else {
                $sort = [
                    $sort => 1
                ];
            }
        }

        $list = $self->connection->find($filters->serialize(), [
            'limit' => $limit,
            'skip' => $skip,
            'sort' => $sort
        ]);

        $count = $self->connection->countDocuments($filters->serialize());

        return [
            'result' => $list->toArray(),
            'meta' => [
                'page' => (int) $page,
                'per_page' => (int) $perPage,
                'total' => $count
            ]
        ];
    }

    /**
     * @param $id
     * @return $this
     */
    public function findOne($id)
    {
        $data = $this->connection->findOne([
            '_id' => new ObjectId($id)
        ]);

        $this->setAttributesValue($data);
        return $this;
    }

    /**
     * @param $id
     * @return $this
     */
    public function findOneBy($filter)
    {
        $data = $this->connection->findOne($filter);

        $this->setAttributesValue($data);
        return $this;
    }

    /**
     * @param $filters
     * @return $this
     */
    public function findBy($filters)
    {
        $data = $this->connection->find($filters);

        $this->setAttributesValue($data);
        return $this;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return array
     */
    public function addError($attribute, $error)
    {
        return $this->errors[$attribute] = $error;
    }
}