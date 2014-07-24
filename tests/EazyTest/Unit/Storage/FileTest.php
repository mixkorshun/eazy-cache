<?php
namespace EazyTest\Unit\Storage;

use Eazy\Cache\Storage\File;

class FileTest extends BaseStorageTest
{

    protected function tearDown()
    {
        shell_exec('rm -Rf ' . TEMP_PATH . '/*');
    }

    /**
     * {@inheritdoc}
     */
    protected function createStorage()
    {
        return new File(TEMP_PATH);
    }
}
