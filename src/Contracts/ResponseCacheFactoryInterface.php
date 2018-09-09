<?php
namespace Juhara\CacheMiddleware\Contracts;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Juhara\ZzzCache\Contracts\CacheableFactoryInterface;

/**
 * interface for helper class for initializing ResponseCache instance
 * @author Zamrony P. Juhara <zamronypj@yahoo.com>
 */
interface ResponseCacheFactoryInterface extends CacheableFactoryInterface
{
    /**
     * set current request
     * @param  Request $requestInstance request instance
     * @return ResponseCacheFactory current instance
     */
    public function request(Request $requestInstance);

    /**
     * set current response
     * @param  Response $responseInstance response
     * @return ResponseCacheFactory   current instance
     */
    public function response(Response $responseInstance);
}
