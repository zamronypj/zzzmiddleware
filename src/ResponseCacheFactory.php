<?php
namespace Juhara\CacheMiddleware;

use Psr\Http\ServerRequestInterface as Request;
use Psr\Http\ResponseInterface as Response;

/**
 * helper class for intializing ResponseCache instance
 * @author Zamrony P. Juhara <zamronypj@yahoo.com>
 */
final class ResponseCacheFactory
{
    /**
     * response instance
     * @var Response
     */
    private $responseInstance;

    /**
     * request instance
     * @var Request
     */
    private $requestInstance;

    /**
     * next middleware
     * @var callable
     */
    private $nextCallback;

    /**
     * time to live in millisecond
     * @var int
     */
    private $ttl = ResponseCache::TTL_DEFAULT;

    /**
     * set current request
     * @param  Request $requestInstance request instance
     * @return ResponseCacheFactory current instance
     */
    public function request(Request $requestInstance)
    {
        $this->requestInstance = $requestInstance;
        return $this;
    }

    /**
     * set current response
     * @param  Response $responseInstance response
     * @return ResponseCacheFactory   current instance
     */
    public function response(Response $responseInstance)
    {
        $this->responseInstance = $responseInstance;
        return $this;
    }

    /**
     * set next middleware
     * @param  callable $nextCallback next middleware
     * @return ResponseCacheFactory    current instance
     */
    public function next(callable $nextCallback)
    {
        $this->nextCallback = $nextCallback;
        return $this;
    }

    /**
     * set current time to live
     * @param  int $ttl time to live
     * @return ResponseCacheFactory current instance
     */
    public function ttl($ttl)
    {
        $this->ttl = $ttl;
        return $this;
    }

    /**
     * initialize ResponseCache
     * @return ResponseCache response cache instance
     */
    public function build()
    {
        return new ResponseCache(
            $this->requestInstance,
            $this->responseInstance,
            $this->nextCallback,
            $this->ttl
        );
    }
}
