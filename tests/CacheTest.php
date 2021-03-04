<?php

namespace Test;

use EasySwoole\Redis\Config\RedisConfig;
use EasySwoole\Redis\Redis;
use Hlhill\PsrCacheRedis\Config\Config;
use Hlhill\PsrCacheRedis\RedisCache;
use PHPUnit\Framework\TestCase;

class CacheTest extends TestCase
{
    protected $client;

    public function setUp()
    {
        parent::setUp();
        $redisClient = new Redis(new RedisConfig([
            'host' => REDIS_HOST,
            'port' => REDIS_PORT,
        ]));
        $redisCacheConfig = new Config([
            'use_unlink' => true
        ]);

        $this->client = new RedisCache($redisClient, $redisCacheConfig);
    }

    public function testSet()
    {
        $this->assertNotFalse($this->client->set('test','cache_test_word'));
    }

    public function testGet()
    {
        $this->assertEquals('cache_test_word', $this->client->get('test'));
    }

    public function testDelete()
    {
        $this->assertNotFalse($this->client->delete('test'));
        $this->assertNull($this->client->get('test'));
        $this->assertEquals('ttl_test', $this->client->get('test','ttl_test'));
    }

    public function testSetMultiple()
    {
        $key1 = 'test1';
        $key2 = 'test2';
        $testMultiple = [
            $key1 => 'hello world',
            $key2 => 'php'
        ];
        $this->assertNotFalse($this->client->setMultiple($testMultiple));
    }

    public function testGetMultiple()
    {
        $key1 = 'test1';
        $key2 = 'test2';
        $testMultiple = [
            $key1 => 'hello world',
            $key2 => 'php'
        ];
        $testKeys = [
            $key1, $key2
        ];
        $data = $this->client->getMultiple($testKeys);
        $this->assertEquals($testMultiple[$key1], $data[$key1]);
        $this->assertEquals($testMultiple[$key2], $data[$key2]);
    }

    public function testDeleteMultiple()
    {
        $key1 = 'test1';
        $key2 = 'test2';
        $testKeys = [
            $key1, $key2
        ];
        $this->assertNotFalse($this->client->deleteMultiple($testKeys));
        $this->assertNull($this->client->get($key1));
        $this->assertNull($this->client->get($key2));
    }

    public function testHas()
    {
        $key = 'test_has';
        $this->client->set($key, 'test_has_value');
        $this->assertTrue($this->client->has($key));
    }

    public function testSetTtl()
    {
        $key = 'test_set_ttl';
        $this->client->set($key,$key.'_value', 5);
        $this->assertTrue($this->client->has($key));
        sleep(6);
        $this->assertFalse($this->client->has($key));
    }

    public function testSetMultipleTtl()
    {
        $key1 = 'test_set_multiple_ttl1';
        $key2 = 'test_set_multiple_ttl2';
        $testMultiple = [
            $key1 => $key1 .'_value',
            $key2 => $key2 .'_value',
        ];
        $this->client->setMultiple($testMultiple,5);
        $this->assertTrue($this->client->has($key1));
        $this->assertTrue($this->client->has($key2));
        sleep(6);
        $this->assertFalse($this->client->has($key1));
        $this->assertFalse($this->client->has($key2));
    }
}