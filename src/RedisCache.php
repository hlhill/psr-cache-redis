<?php

namespace Hlhill\PsrCacheRedis;

use EasySwoole\Redis\Redis;
use Hlhill\PsrCacheRedis\Config\Config;
use Psr\SimpleCache\CacheInterface;

class RedisCache implements CacheInterface
{
    /**
     * @var Redis
     */
    private $client;

    /**
     * @var Config
     */
    private $config;

    /**
     * RedisCache constructor.
     * @param Redis $client
     * @param Config $config
     */
    public function __construct(Redis $client, Config $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    /**
     * @param string $key
     * @param null $default
     * @return bool|mixed|string|null
     */
    public function get($key, $default = null)
    {
        $value = $this->client->hGet($this->config->getCacheKeyHeader() , $key);
        $value = $this->decode($value);
        if (is_null($value)) {
            return $default;
        } else if ($value === false) {
            $this->delete($key);
            return $default;
        }
        return $value;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param null $ttl
     * @return bool|string
     */
    public function set($key, $value, $ttl = null)
    {
        return $this->client->hSet($this->config->getCacheKeyHeader(), $key, $this->encode($value, $ttl));
    }

    /**
     * @param string $key
     * @return bool
     */
    public function delete($key)
    {
        return $this->deleteMultiple([$key]);
    }

    /**
     * @return bool
     */
    public function clear()
    {
        if ($this->config->getUseUnlink()) {
            $this->client->unlink($this->config->getCacheKeyHeader());
            return true;
        }
        $cursor = "0";
        do {
            $data = $this->client->hScan($this->config->getCacheKeyHeader(), $cursor, null, $this->config->getClearCount());
            if (is_array($data) && count($data) > 0) {
                $this->deleteMultiple(array_keys($data));
            }
        } while ($cursor != "0");

        return true;
    }

    /**
     * @param iterable $keys
     * @param null $default
     * @return array|iterable
     */
    public function getMultiple($keys, $default = null)
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $this->get($key, $default);
        }

        return $result;
    }

    /**
     * @param iterable $values
     * @param null $ttl
     * @return bool
     */
    public function setMultiple($values, $ttl = null)
    {
        foreach ($values as $key => $value) {
            if (!$this->set($key, $value, $ttl)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param iterable $keys
     * @return bool
     */
    public function deleteMultiple($keys)
    {
        $keys = $this->getMultipleKeys($keys);
        return $this->client->hDel($this->config->getCacheKeyHeader(), ...$keys) === count($keys);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return (bool)$this->get($key);
    }

    /**
     * @param $keys
     * @return bool[]|string[]
     */
    private function getMultipleKeys($keys)
    {
        return is_array($keys) ? $keys : [$keys];
    }

    /**
     * @param $value
     * @param null $ttl
     * @return false|string
     */
    private function encode($value, $ttl = null)
    {
        return json_encode([
            'v' => $value,
            't' => is_null($ttl) ? -1 : $ttl + time()
        ]);
    }

    /**
     * @param $value
     * @return bool|mixed|null
     */
    private function decode($value)
    {
        if (is_null($value)) {
            return null;
        }
        $value = json_decode($value, true);
        if (!$this->ttlCheck($value['t'])) {
            return false;
        }
        return $value['v'];
    }

    /**
     * @param $time
     * @return bool
     */
    private function ttlCheck($time)
    {
        if ($time != -1 && $time < time()) {
            return false;
        }
        return true;
    }
}