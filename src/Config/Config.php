<?php

namespace Hlhill\PsrCacheRedis\Config;

use EasySwoole\Spl\SplBean;

class Config extends SplBean
{

    protected $use_unlink = true;
    protected $clear_count = 1000;
    protected $cache_key_header = 'redis_cache_header_551a2e2f0e53350388be22700aa56141';

    /**
     * @return bool
     */
    public function getUseUnlink(): bool
    {
        return $this->use_unlink;
    }

    /**
     * @param boolean $useUnlink
     */
    public function setUseUnlink(bool $useUnlink): void
    {
        $this->use_unlink = $useUnlink;
    }

    /**
     * @param int $clearCount
     */
    public function setClearCount(int $clearCount): void
    {
        $this->clear_count = $clearCount;
    }

    /**
     * @return int
     */
    public function getClearCount():int
    {
        return $this->clear_count;
    }

    /**
     * @param string $cacheKeyHeader
     */
    public function setCacheKeyHeader(string $cacheKeyHeader): void
    {
        $this->cache_key_header = $cacheKeyHeader;
    }

    public function getCacheKeyHeader(): string
    {
        return $this->cache_key_header;
    }
}