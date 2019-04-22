<?php

namespace peang\jobs;

use Pheanstalk\Pheanstalk;

/**
 * Class PheanstalkJob
 * @author  Irvan Setiawan <peang.cookie@gmail.com>
 */
class PheanstalkJob
{
    /**
     * @var Pheanstalk
     */
    private $pheanstalk;

    /**
     * @var string
     */
    public $host = null;

    /**
     * @var string
     */
    public $tube = null;

    /**
     * PheanstalkJob constructor.
     * @param $host
     * @param $tube
     */
    public function __construct($host, $tube)
    {
        $this->host = $host;
        $this->tube = $tube;

        $this->pheanstalk = Pheanstalk::create($host);
    }

    /**
     * @param $workerName
     * @param $payload
     */
    public function enqueue($workerName, $payload)
    {
        $payload['__worker_class'] = $workerName;
        $this->pheanstalk->useTube($this->tube)->put(json_encode($payload));
    }
}