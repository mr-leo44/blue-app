<?php

use \Symfony\Component\Cache\Adapter\FilesystemAdapter;
use \Symfony\Contracts\Cache\ItemInterface;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();
class Cacher
{
    private $cache;
    private $prefix = "";
    private $disable;
    private int $expireAt;

    public function __construct()
    {
        $this->cache = new FilesystemAdapter();
        $this->expireAt = isset($_ENV['CACHE_EXPIRE_AT']) ? intval($_ENV['CACHE_EXPIRE_AT']) : 60;
        $this->disable =  filter_var($_ENV['ENABLE_CACHE'] ?? false, FILTER_VALIDATE_BOOLEAN);
    }

    public function setPrefix(string $prefix)
    {
        $this->prefix = $prefix;
    }

    public  function get(array $cacheKey, $callback)
    {
        if (empty($this->disable) or is_null($this->disable) or $this->disable == false) {
            return $callback();
        }

        $finalKey = implode('-', [$this->prefix, ...$cacheKey]);

        $value = $this->cache->get($finalKey, function (ItemInterface $item) use ($callback) {
            $item->expiresAfter($this->expireAt);
            return $callback();
        });
        return $value;
    }
}
