<?php
namespace peang\logger;

use Monolog\Logger as MonologLogger;

/**
 * @package penag\logger
 * @author  Irvan Setiawan <peang.cookie@gmail.com>
 */
class Logger
{
    /**
     * @return MonologLogger
     */
    function __invoke()
    {
        $logger = new MonologLogger('base_logger');
        $file_handler = new \Monolog\Handler\StreamHandler("../logs/app.log");
        $logger->pushHandler($file_handler);
        return $logger;
    }
}