<?php
namespace Eazy\Cache;

trait CacheAwareTrait
{
    /**
     * @var Cache $cache cache component
     */
    protected $cache;

    public function setCache(Cache $cache)
    {
        $this->cache = $cache;
    }
}
