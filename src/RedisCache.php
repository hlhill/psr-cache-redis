<?php

namespace Hlhill\PsrCacheRedis;

use EasySwoole\Redis\Redis;
use Psr\SimpleCache\CacheInterface;

class RedisCache implements CacheInterface
{
    private $client;

    public function __construct(Redis $client)
    {
        $this->client = $client;
    }

    public function get($key, $default = null)
    {
        $value = $this->client->get($key);
        if (is_null($value)) {
            $value = $default;
        }
        return $value;
    }

    public function set($key, $value, $ttl = null)
    {
        // TODO: Implement set() method.
    }

    public function delete($key)
    {
        // TODO: Implement delete() method.
    }

    public function clear()
    {
        // TODO: Implement clear() method.
    }

    public function getMultiple($keys, $default = null)
    {
        // TODO: Implement getMultiple() method.
    }

    public function setMultiple($values, $ttl = null)
    {
        // TODO: Implement setMultiple() method.
    }

    public function deleteMultiple($keys)
    {
        // TODO: Implement deleteMultiple() method.
    }

    public function has($key)
    {
        // TODO: Implement has() method.
    }
}