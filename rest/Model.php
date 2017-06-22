<?php
namespace base\rest;

use base\helpers\Helpers;
use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * Base Model to use for query
 * @package base\rest
 * @author  Irvan Setiawan <peang.cookie@gmail.com>
 */
abstract class Model extends EloquentModel
{
    /**
     * Model constructor.
     */
    public function __construct()
    {
        $this->setTable($this->getTableName());

        parent::__construct();
    }

    /**
     * @param $params
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function findOne($params)
    {
        $primaryKey = self::getPrimaryKey();
        $query = self::query();

        if (Helpers::isArrayAssociative($params)) {
            foreach ($params as $k => $v) {
                $query->orWhere($k, '=', $v)->first();
            }
        } else {
            $query->where($primaryKey, '=', $params);
        }

        return $query->get()->toArray();
    }

    /**
     * @return mixed
     */
    public function getPrimaryKey()
    {
        $classname = get_called_class();
        $modelClass = new $classname();

        return $modelClass->primary_key;
    }

    /**
     * @return string
     */
    abstract function getTableName();
}