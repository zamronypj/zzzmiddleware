<?php
namespace Juhara\CacheMiddleware;

use Psr\Http\ServerRequestInterface as Request;
use Psr\Http\ResponseInterface as Response;

final class ResponseCacheFactory
{
    private $responseInstance;
    private $requestInstance;
    private $nextCallback;
    private $ttl = ResponseCache::TTL_DEFAULT;

    public function request(Request $requestInstance)
    {
        $this->requestInstance = $requestInstance;
    }

    public function response(Response $responseInstance)
    {
        $this->responseInstance = $responseInstance;
    }

    public function next(callable $nextCallback)
    {
        $this->nextCallback = $nextCallback;
    }

    public function ttl($ttl)
    {
        $this->ttl = $ttl;
    }

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
