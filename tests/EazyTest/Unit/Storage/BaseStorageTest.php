<?php
namespace EazyTest\Unit\Storage;


use Eazy\Cache\Storage\StorageInterface;

abstract class BaseStorageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var StorageInterface
     */
    protected $storage;


    public function setUp()
    {
        $this->storage = $this->createStorage();
    }

    /**
     * @return StorageInterface
     */
    abstract protected function createStorage();

    public function testAddValue()
    {
        $this->assertTrue($this->storage->add('add-key', 'value1', 1), 'Adding first value should be successful.');
        $this->assertFalse($this->storage->add('add-key', 'value2'), 'Adding value second time should fail.');

        $this->assertEquals('value1', $this->storage->get('add-key'));

    }

    public function testSetValue()
    {
        $this->assertTrue($this->storage->set('set-key', 'value1'), 'Set value should be successful.');
        $this->assertTrue($this->storage->set('set-key', 'value2'), 'Second time set value should be successful.');

        $this->assertEquals('value2', $this->storage->get('set-key'));
    }

    public function testDeleteValue()
    {
        $this->assertFalse($this->storage->del('del-key'));

        $this->storage->set('del-key', 'value');
        $this->assertTrue($this->storage->del('del-key'));

        $this->assertNotEquals('value', $this->storage->get('del-key'));
    }

    public function testCacheValues()
    {
        $obj = new \DateTime();
        $arr = array(
            'key1' => 'value1',
            'key2' => 'value2'
        );

        $this->storage->set('obj', new \DateTime());
        $this->storage->set('arr', $arr);

        $this->assertEquals($obj, $this->storage->get('obj'));
        $this->assertEquals($arr, $this->storage->get('arr'));
    }

    public function testExpireValue()
    {
        if (!function_exists('override_function')) {
            $this->markTestSkipped('Need apd extension.');
            return;
        }

        rename_function('time', 'php_time');
        override_function('time', '', 'static $time = 0; return $time++;');

        $this->storage->set('expire-key', 'value1', 1);

        $this->assertFalse($this->storage->has('expire-key'), 'Value should be expired');
        $this->assertNotEquals('value1', $this->storage->get('expire-key'), 'Value should not saved.');

        $this->assertTrue($this->storage->add('expire-key', 'value2'), 'Adding value to expire key should be successful.');

        rename_function('php_time', 'time');
    }
}
