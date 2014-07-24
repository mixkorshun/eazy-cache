<?php
namespace Eazy\Cache;

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

        $value = call_user_func($callable);
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
        call_user_func($callable);
        $value = ob_get_clean();

        $this->storage->set($key, $value, $ttl);

        print $value;
    }

    public function getStorage()
    {
        return $this->storage;
    }
}
