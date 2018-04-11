# zzzmiddleware
Cache middleware for Slim Framework 3.0

# Requirement
- [PHP >= 5.4](https://php.net)
- [Composer](https://getcomposer.org)
- [Slim Framework 3.0](https://slimframework.com)
- [ZzzCache](https://github/zamronypj/zzzcache)

# Installation
Run through composer

    $ composer require juhara/zzzmiddleware

# How to use

### Register CacheMiddleware to Slim Dependency Container

    <?php
    ...
    use Juhara\ZzzCache\Contracts\CacheInterface;
    use Juhara\ZzzCache\Cache;
    use Juhara\ZzzCache\Storages\File;
    use Juhara\ZzzCache\Helpers\TimeUtility;
    use Juhara\ZzzCache\Helpers\Md5Hash;

    $container[CacheInterface::class] = function ($c) {
        // create a file-based cache where all cache
        // files is stored in directory name
        // app/storages/cache with
        // filename prefixed with string 'cache'
        return new Cache(
            new File(
                new Md5Hash(),
                'app/storages/cache/',
                'cache'
            ),
            new TimeUtility()
        );
    };

    use Juhara\CacheMiddleware\CacheMidleware;
    use Juhara\CacheMiddleware\ResponseCacheFactory;

    $container[CacheMiddleware::class] = function ($c) {
        $cache = $c->get(CacheInterface::class);
        $factory = new ResponseCacheFactory();
        return new CacheMiddleware($cache, $factory);
    };

### Add CacheMiddleware to Slim Application

    use Slim\App;
    use  Juhara\CacheMiddleware\CacheMiddleware;

    $app = new App($settings);
    ...
    $app->add(CacheMiddleware::class);

### Create Proper Configuration

If using `Juhara\ZzzCache\Storages\File` as storage, then you need to make sure
that directory of cache is writeable by web server.

## Example

See [example application](https://github.com/zamronypj/zzzappexample) to understand
how to use CacheMiddleware.

## Contributing

Just create PR if you want to improve it.

Thank you.
