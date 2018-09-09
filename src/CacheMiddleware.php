<?php

namespace Juhara\CacheMiddleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Juhara\ZzzCache\Contracts\CacheInterface;
use Juhara\CacheMiddleware\Contracts\ResponseCacheFactoryInterface;

/**
 * cache middleware that retrieve content from cache if available
 * or if not found, pass execution to next middleware and store response to
 * cache

 * @author Zamrony P. Juhara <zamronypj@yahoo.com>
 */
class CacheMiddleware
{
    /**
     * Cache instance
     * @var CacheInterface
     */
    private $cache;

    /**
     * response cache factory instance
     * @var ResponseCacheFactoryInterface
     */
    private $responseCacheFactory;

    /**
     * Contructor of middleware
     * @param CacheInterface       $cache   cache instance
     * @param ResponseCacheFactory $factory response cache factory instance
     */
    public function __construct(
        CacheInterface $cache,
        ResponseCacheFactoryInterface $factory
    ) {
        $this->cache = $cache;
        $this->responseCacheFactory = $factory;
    }

    /**
     * add url to cacheable item if not registered
     *
     * @param [type]   $url      current full url
     * @param Request  $request  request instance
     * @param Response $response response instance
     * @param callable $next     next middleware
     */
    private function addCacheIfNotExist(
        $url,
        Request $request,
        Response $response,
        callable $next
    ) {
        if (! $this->cache->has($url)) {
            $cacheable = $this->responseCacheFactory
                ->request($request)
                ->response($response)
                ->build($next, null);
            $this->cache->add($url, $cacheable);
        }
    }

    /**
     * get response content from cache of available or get response
     * from next middleware
     *
     * @param  Request  $request  request instance
     * @param  Response $response response instance
     * @param  callable $next     next middleware
     * @return string response content
     */
    private function getResponseFromCacheIfAvail(
        Request $request,
        Response $response,
        callable $next
    ) {
        $url = (string) $request->getUri();
        $this->addCacheIfNotExist($url, $request, $response, $next);
        return $this->cache->get($url);
    }

    /**
     * method that is called when our middleware is triggered
     * @param  Request  $request  request instance
     * @param  Response $response response instance
     * @param  callable $next     next middleware
     * @return Response           response
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $content = $this->getResponseFromCacheIfAvail($request, $response, $next);
        //we have full content so
        //rewind to make sure that we override any previously stored content.
        $response->getBody()->rewind();
        $response->getBody()->write($content);
        return $response;
    }
}
