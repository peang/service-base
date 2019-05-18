<?php

namespace peang\contracts;

/**
 * Interface PheanstalkJobInterface
 * @package peang\contracts
 */
interface PheanstalkJobInterface
{
    /**
     * @return mixed
     */
    public function init();

    /**
     * @param $payload
     * @return bool
     */
    public function run($payload);

    /**
     * @param $payload
     * @return bool
     */
    public function done();
}