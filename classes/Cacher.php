<?php

use \Symfony\Component\Cache\Adapter\FilesystemAdapter;
use \Symfony\Contracts\Cache\ItemInterface;

class Cacher
{
    private $cache;

    public function __construct()
    {
        $this->cache = new FilesystemAdapter();
    }

    public  function push(string $cacheKey, $callback)
    {
        $value = $this->cache->get($cacheKey, function (ItemInterface $item) use ($callback) {
            $item->expiresAfter(3600);

            return $callback();
        });

        return $value;
    }
}
