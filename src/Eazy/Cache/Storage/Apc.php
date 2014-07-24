<?php
namespace Eazy\Cache\Storage;

use Eazy\Cache\Exception\RuntimeException;

class Apc implements StorageInterface
{
    public function __construct()
    {
        if (!extension_loaded('apc')) {
            throw new RuntimeException('Cannot use Apc cache storage. Apc extension is not loaded.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function add($key, $value, $ttl = 0)
    {
        return apc_add($key, $value, $ttl);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $ttl = 0)
    {
        return apc_store($key, $value, $ttl);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        return apc_fetch($key, $success);
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        return apc_exists($key);
    }

    /**
     * {@inheritdoc}
     */
    public function del($key)
    {
        return apc_delete($key);
    }
}
