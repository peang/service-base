<?php
namespace peang\base\middlewares\auth;

use peang\base\abstraction\JsonResponse;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Middleware\JwtAuthentication;

/**
 * This will authenticate all request before going to request action
 *
 * @package base\middleware
 * @author  Irvan Setiawan <peang.cookie@gmail.com>
 */
class JwtAuthenticationMiddleware extends JwtAuthentication
{
    /**
     * @param RequestInterface|Request $request
     * @param ResponseInterface|Response $response
     * @param callable $next
     *
     * @return ResponseInterface
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        $scheme = $request->getUri()->getScheme();
        $host = $request->getUri()->getHost();

        /* If rules say we should not authenticate call next and return. */
        if (false === $this->shouldAuthenticate($request)) {
            return $next($request, $response);
        }

        /* HTTP allowed only if secure is false or server is in relaxed array. */
        if ("https" !== $scheme && true === $this->getSecure()) {
            if (!in_array($host, $this->getRelaxed())) {
                $message = sprintf(
                    "Insecure use of middleware over %s denied by configuration.",
                    strtoupper($scheme)
                );
                throw new \RuntimeException($message);
            }
        }

        /* If token cannot be found return with 401 Unauthorized. */
        if (false === $token = $this->fetchToken($request)) {
            return JsonResponse::build($response, $this->message, 401);
        }

        /* If token cannot be decoded return with 401 Unauthorized. */
        if (false === $decoded = $this->decodeToken($token)) {
            return JsonResponse::build($response, $this->message, 401);
        }

        /* If callback returns false return with 401 Unauthorized. */
        if (is_callable($this->getCallback())) {
            $params = ["decoded" => $decoded];
            if (false === $this->getCallback()($request, $response, $params)) {
                return JsonResponse::build($response, $this->message ? $this->message : "Callback returned false", 401);
            }
        }

        /* Add decoded token to request as attribute when requested. */
        if ($this->getAttribute()) {
            $request = $request->withAttribute($this->getAttribute(), $decoded);
        }

        /* Everything ok, call next middleware and return. */
        return $next($request, $response);
    }
}