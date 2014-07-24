<?php
namespace EazyTest\Unit\Storage;

use Eazy\Cache\Storage\Memory;

class MemoryTest extends BaseStorageTest
{

    /**
     * {@inheritdoc}
     */
    protected function createStorage()
    {
        return new Memory();
    }
}
