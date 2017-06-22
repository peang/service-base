<?php
namespace base\abstraction;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * @package base\abstraction
 * @author  Irvan Setiawan <irvan.setiawan@tafern.com>
 */
abstract class Route
{
    const METHOD_GET = 'get';
    const METHOD_POST = 'post';
    const METHOD_PUT = 'put';
    const METHOD_DELETE = 'delete';

    /**
     * @var array
     */
    protected static $methods = [
        self::METHOD_GET, self::METHOD_DELETE, self::METHOD_POST, self::METHOD_PUT
    ];

    /**
     * @var array
     */
    public $pathData;

    /**
     * @return void
     */
    abstract function register();

    /**
     * @param $path
     * @param $method
     * @param $permission
     * @param null $controller
     * @param $action
     */
    protected function add($path, $method, $permission, $controller = null, $action)
    {
        if (!in_array($method, self::$methods)) {
            throw new InvalidConfigurationException('Unknown Method Used', 500);
        }

        array_push($this->pathData, [
            'url' => $path,
            'method' => $method,
            'permission' => $permission,
            'controller' => $controller,
            'action' => $action
        ]);
    }

    public static function registerRoutes()
    {
        $classes = array();

        foreach( get_declared_classes() as $class ) {
            var_dump($class);
        }

        die;
    }
}