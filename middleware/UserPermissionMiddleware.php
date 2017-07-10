<?php
namespace base\middleware;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Route;

class UserPermissionMiddleware
{
    public function __construct($permission = [])
    {

    }

    /**
     * @param Request $request
     * @param Response $response
     * @param Route $next
     * @return Response
     */
    function __invoke(Request $request, Response $response, Route $next)
    {
        /** @var Response $response */
        $response = $next($request, $response);

        return $response;
    }
}