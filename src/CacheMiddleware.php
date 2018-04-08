<?php

namespace Juhara\CacheMiddleware;

use Psr\Http\ServerRequestInterface as Request;
use Psr\Http\ResponseInterface as Response;
use Juhara\SlimCache\Contracts\CacheInterface;

class CacheMiddleware
{
    private $cache;

    private $responseCacheFactory;

    public function __construct(CacheInterface $cache, ResponseCacheFactory $factory)
    {
        $this->cache = $cache;
        $this->responseCacheFactory = $factory;
    }

    private function addCacheIfNotExist(
        $url,
        Request $request,
        Response $response,
        callable $next
    ) {
        if (! $this->cache->has($url)) {
            $cacheble = $this->responseCacheFactory
                ->request($request)
                ->response($response)
                ->next($next)
                ->build();
            $this->cache>add($url, $cacheable);
        }
    }

    public function __invoke(Request $request, Response $response, callable $next)
    {
        $url = (string) $request->getUri();
        $this->addCacheIfNotExist($url, $request, $response, $next);
        return $response->getBody()->write($this->cache->get($url));
    }
}
