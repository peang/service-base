<?php

namespace peang\base\abstraction;

use Slim\Http\Response;

/**
 * @package base\abstraction
 * @author  Irvan Setiawan <irvan.setiawan@tafern.com>
 */
class JsonResponse
{
    /**
     * @param Response $response
     * @param $message
     * @param $code
     * @param null $data
     *
     * @return Response
     */
    public static function build(Response $response, $message, $code, $data = null) {

        return $response->withStatus($code)
                        ->withJson(array_filter([
                            'message' => $message,
                            'code' => $code,
                            'data' => $data
                        ]));
    }
}