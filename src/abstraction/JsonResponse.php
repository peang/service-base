<?php

namespace peang\abstraction;

use peang\rest\Model;
use Slim\Http\Response;

/**
 * @package base\abstraction
 * @author  Irvan Setiawan <peang.cookie@gmail.com>
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
    public static function build(Response $response, $message, $code, $data = null)
    {
        if ($data instanceof Model) {
            $data = $data->getAttributes();
        }

        return $response->withStatus($code)
                        ->withJson(array_filter([
                            'message' => $message,
                            'code' => $code,
                            'data' => $data
                        ]));
    }
}
