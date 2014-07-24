<?php
namespace Eazy\Cache;

interface CacheAwareInterface
{
    /**
     * @param Cache $cache
     */
    public function setCache(Cache $cache);
}
