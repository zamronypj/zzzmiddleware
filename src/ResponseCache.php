<?php
namespace Juhara\CacheMiddleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Juhara\ZzzCache\Contracts\Cacheable;

/**
 * Cache implementation for response
 * @author Zamrony P. Juhara <zamronypj@yahoo.com>
 */
final class ResponseCache implements Cacheable
{
    /**
     * default time to live (15 minutes)
     * @var int
     */
    const TTL_DEFAULT = 15 * 60 * 1000;

    /**
     * request instance     *
     * @var Request
     */
    private $request;

    /**
     * response instance
     * @var Response
     */
    private $response;

    /**
     * next middleware
     * @var callable
     */
    private $next;

    /**
     * time to live in millisecond
     * @var int
     */
    private $ttl;

    /**
     * constructor
     * @param Request  $request  request instance
     * @param Response $response response instance
     * @param callable $next     next middleware
     * @param int      $ttl      time to live in millisecond
     */
    public function __construct(
        Request $request,
        Response $response,
        callable $next,
        $ttl = self::TTL_DEFAULT
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->next = $next;
        $this->ttl;
    }

    /**
     * get data in serialized format
     * @return string data in serialized format
     */
    public function data()
    {
        $next = $this->next;
        $response = $next($this->request, $this->response);
        return (string) $response->getBody();
    }

    /**
     * retrieve time to live in millisecond
     * @return [int] time to live of cache
     */
    public function ttl()
    {
        return $this->ttl;
    }

}
