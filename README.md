# 基于easyswoole Redis客户端的缓存驱动
基于easyswoole Redis客户端实现的遵循PSR-16 CacheInterface的缓存驱动

## 使用
#### Config
```php
$redisClient = new \EasySwoole\Redis\Redis(new \EasySwoole\Redis\Config\RedisConfig([
            'host' => REDIS_HOST,
            'port' => REDIS_PORT,
        ]));
$redisCacheConfig = new \Hlhill\PsrCacheRedis\Config\Config();
$client = new \Hlhill\PsrCacheRedis\RedisCache($redisClient, $redisCacheConfig);
```

#### set
```php
$key = 'key';
$value = 'value';
$ttl = 600;
$client->set($key, $value, $ttl);
```

#### get
```php
$key = 'key';
$default = 'defaultValue';
return $client->get($key, $default);
```
#### has
```php
$key = 'key';
return $client->has($key);
```

#### delete
```php
$key = 'key';
return $client->delete($key);
```

#### 批量设置
```php
$cache = [
    'key1' => 'value1',
    'key2' => 'value2',
    'key3' => 'value3',
];
$ttl = 600;
$client->setMultiple($cache, $ttl);
```

#### 批量获取
```php
$keys = [
    'key1',
    'key2',
    'key3',
];
$default = 'defaultValue';
return $client->getMultiple($keys, $default);
```

#### 批量删除
```php
$keys = [
    'key1',
    'key2',
    'key3',
];
$client->deleteMultiple();
```

#### 清除缓存
```php
$client->clear();
```