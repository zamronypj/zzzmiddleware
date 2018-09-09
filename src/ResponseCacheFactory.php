<?php
namespace Juhara\CacheMiddleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Juhara\CacheMiddleware\Contracts\ResponseCacheFactoryInterface;

/**
 * helper class for initializing ResponseCache instance
 * @author Zamrony P. Juhara <zamronypj@yahoo.com>
 */
final class ResponseCacheFactory implements ResponseCacheFactoryInterface
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
     * create Cacheable interface instance
     * @param callable $nextCallback callback to get data in Cacheable instance
     * @param int   $ttl   time to live
     * @return Cacheable new instance of Cacheable interface
     */
    public function build($nextCallback, $ttl)
    {
        $this->ttl = empty($ttl) ? ResponseCache::TTL_DEFAULT : $ttl;
        return new ResponseCache(
            $this->requestInstance,
            $this->responseInstance,
            $nextCallback,
            $this->ttl
        );
    }
}
