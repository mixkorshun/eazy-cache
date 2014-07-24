<?php
namespace Eazy\Cache\Storage;


class Memory implements StorageInterface
{

    private $data = array();

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
        $this->data[$key] = array(
            'timestamp' => time(),
            'ttl' => $ttl,
            'value' => $value
        );

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        if ($this->has($key)) {
            return $this->data[$key];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        if (!isset($this->data[$key])) {
            return false;
        }

        $value = $this->data[$key];

        if ($this->isDataExpired($value)) {
            unset($this->data[$key]);
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function del($key)
    {
        $has = $this->has($key);

        if ($has) {
            unset($this->data[$key]);
        }

        return $has;
    }

    private function isDataExpired(array $data)
    {
        return $data['ttl'] !== 0 && time() - $data['timestamp'] > $data['ttl'];
    }
}
