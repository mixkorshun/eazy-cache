<?php
namespace Eazy\Cache\Storage;

use Eazy\Cache\Exception\RuntimeException;

class XCache implements StorageInterface
{
    public function __construct()
    {
        if (!extension_loaded('xcache')) {
            throw new RuntimeException('Cannot use Apc cache storage. Apc extension is not loaded.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function add($key, $value, $ttl = 0)
    {
        trigger_error('XCache add method is not monatomic. Please use another cache storage.');

        if (xcache_isset($key)) {
            return false;
        }

        return $this->set($key, $value, $ttl);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $ttl = 0)
    {
        return xcache_set($key, $value, $ttl);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        return xcache_get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        return xcache_isset($key);
    }

    /**
     * {@inheritdoc}
     */
    public function del($key)
    {
        return xcache_unset($key);
    }
}
