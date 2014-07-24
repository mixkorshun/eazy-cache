<?php
namespace Eazy\Cache\Storage;

interface StorageInterface
{
    public function add($key, $value, $ttl = 0);

    public function set($key, $value, $ttl = 0);

    public function get($key);

    public function has($key);

    public function del($key);
}
