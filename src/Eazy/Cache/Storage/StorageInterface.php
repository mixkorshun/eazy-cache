<?php
namespace Eazy\Cache\Storage;

/**
 * Cache storage interface
 */
interface StorageInterface
{
    /**
     * Add cache item. If item already exist in storage return false.
     *
     * @param string $key
     * @param mixed $value
     * @param int $ttl
     * @return bool
     */
    public function add($key, $value, $ttl = 0);

    /**
     * Set cache item.
     *
     * @param string $key
     * @param mixed $value
     * @param int $ttl
     * @return bool
     */
    public function set($key, $value, $ttl = 0);

    /**
     * Get cache item
     *
     * @param string $key
     * @return mixed
     */
    public function get($key);

    /**
     * Check cache item.
     *
     * @param string $key
     * @return bool
     */
    public function has($key);

    /**
     * Delete cache item.
     *
     * @param string $key
     * @return bool
     */
    public function del($key);
}
