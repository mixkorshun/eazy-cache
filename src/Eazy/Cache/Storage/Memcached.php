<?php
namespace Eazy\Cache\Storage;

use Eazy\Cache\Exception\RuntimeException;

class Memcached implements StorageInterface
{
    /** @var \Memcached $engine */
    private $engine;

    public function __construct(\Memcached $engine)
    {
        if (!extension_loaded('memcached')) {
            throw new RuntimeException('Cannot use memcached cache storage. Memcached extension is not loaded.');
        }

        $this->engine = $engine;
    }

    /**
     * {@inheritdoc}
     */
    public function add($key, $value, $ttl = 0)
    {
        return $this->engine->add($key, $value, $ttl);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $ttl = 0)
    {
        return $this->engine->set($key, $value, $ttl);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        return $this->engine->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        return $this->engine->get($key) !== false || $this->engine->getResultCode() != \Memcached::RES_NOTFOUND;
    }

    /**
     * {@inheritdoc}
     */
    public function del($key)
    {
        return $this->engine->delete($key);
    }
}
