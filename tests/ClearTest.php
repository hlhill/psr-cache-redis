<?php


namespace Test;


use EasySwoole\Redis\Config\RedisConfig;
use EasySwoole\Redis\Redis;
use Hlhill\PsrCacheRedis\Config\Config;
use Hlhill\PsrCacheRedis\RedisCache;
use PHPUnit\Framework\TestCase;

class ClearTest extends TestCase
{
    protected $redisClient;

    public function setUp()
    {
        parent::setUp();
        $this->redisClient = new Redis(new RedisConfig([
            'host' => REDIS_HOST,
            'port' => REDIS_PORT,
        ]));
    }

    public function testUnlink()
    {
        $redisCache = new RedisCache($this->redisClient, new Config([
            'clear_count' => 5
        ]));
        $redisCache->set('test1',123);
        $redisCache->set('test2',123);
        $redisCache->set('test3',123);
        $redisCache->set('test4',123);
        $redisCache->set('test5',123);
        $redisCache->set('test6',123);
        $redisCache->clear();
        $this->assertFalse($redisCache->has('test6'));
    }

    public function testNoUnlink()
    {
        $redisCache = new RedisCache($this->redisClient, new Config([
            'use_unlink' => false,
            'clear_count' => 1
        ]));
        $redisCache->set('test1',1234);
        $redisCache->set('test2',1234);
        $redisCache->set('test3',1234);
        $redisCache->set('test4',1234);
        $redisCache->set('test5',1234);
        $redisCache->set('test6','abcedelkjfalkjfaskdljfalkjdfalskdjfjaslkdfjaslkfjaslkfjdasldkfjasldfkj');
        $redisCache->clear();
        $this->assertFalse($redisCache->has('test6'));
    }
}