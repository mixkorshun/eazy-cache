<?php
namespace Eazy\Cache\Storage;

use Eazy\Cache\Exception\RuntimeException;

class File implements StorageInterface
{
    private $path;
    private $options = array(
        'autoremove' => true,
        'mkdir' => false,
        'chmod' => null
    );

    public function __construct($path, array $options = array())
    {
        if (!is_dir($path) || !is_readable($path) || !is_writable($path)) {
            throw new RuntimeException('Cache path should be directory with available read/write access.');
        }

        $this->path = $path;
        $this->options = array_merge($this->options, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function add($key, $value, $ttl = 0)
    {
        if ($this->has($key)) {
            return false;
        }

        return $this->set($key, $value, $ttl);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $ttl = 0)
    {
        $filename = $this->keyToFile($key);
        $data = array(
            'timestamp' => time(),
            'ttl' => $ttl,
            'value' => $value
        );

        return $this->writeCacheFile($filename, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        $filename = $this->keyToFile($key);

        if (!file_exists($filename)) {
            return null;
        }

        $data = @unserialize(file_get_contents($filename));

        if (!$data) {
            throw new RuntimeException('Cache file is invalid.');
        }

        if ($this->isDataExpired($data)) {
            $this->expireCacheFile($filename);
            return null;
        }

        return $data['value'];
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        $filename = $this->keyToFile($key);

        if (!file_exists($filename)) {
            return false;
        }

        $data = @unserialize(file_get_contents($filename));

        if (!$data) {
            throw new RuntimeException('Cache file is invalid.');
        }

        if ($this->isDataExpired($data)) {
            $this->expireCacheFile($filename);
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function del($key)
    {
        $filename = $this->keyToFile($key);

        if (file_exists($filename)) {
            return unlink($filename);
        }

        return false;
    }

    protected function keyToFile($key)
    {
        $parts = explode(':', $key);
        $name = array_pop($parts);

        $path = implode('/', $parts);
        $path = $this->path . ($path ? '/' . $path : '');

        return $path . '/' . md5($name) . '.cache';
    }

    private function writeCacheFile($filename, $data)
    {
        if ($this->options['mkdir']) {
            $dir = pathinfo($filename, PATHINFO_DIRNAME);

            if (!file_exists($dir)) {
                $mod = $this->options['chmod'] ? $this->options['chmod'] : 0777;
                mkdir($dir, $mod);
            }
        }

        $result = file_put_contents($filename, serialize($data), LOCK_EX) !== false;

        if ($result && $this->options['chmod']) {
            chmod($filename, $this->options['chmod']);
        }

        return $result;
    }

    private function expireCacheFile($filename)
    {
        if ($this->options['autoremove']) {
            unlink($filename);
        }
    }

    private function isDataExpired(array $data)
    {
        return $data['ttl'] !== 0 && time() - $data['timestamp'] > $data['ttl'];
    }
}
