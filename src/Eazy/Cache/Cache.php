<?php
namespace Eazy\Cache;

use Eazy\Cache\Exception\RuntimeException;
use Eazy\Cache\Storage\StorageInterface;

class Cache
{
    private $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function value($key, $callable, $ttl = 0)
    {
        if ($this->storage->has($key)) {
            return $this->storage->get($key);
        }

        try {
            $value = call_user_func($callable);
        } catch (\Exception $e) {
            throw new RuntimeException('Cannot get value for cache.', 0, $e);
        }

        $this->storage->set($key, $value, $ttl);

        return $value;
    }

    public function output($key, $callable, $ttl)
    {
        if ($this->storage->has($key)) {
            print $this->storage->get($key);
            return;
        }

        ob_start();
        try {
            call_user_func($callable);
        } catch (\Exception $e) {
            throw new RuntimeException('Cannot get value for cache.', 0, $e);
        }
        $value = ob_get_clean();

        $this->storage->set($key, $value, $ttl);

        print $value;
    }

    public function getStorage()
    {
        return $this->storage;
    }
}
