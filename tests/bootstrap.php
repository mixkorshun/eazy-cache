<?php
/**
 * @var \Composer\Autoload\ClassLoader $autoload
 */
$autoload = require dirname(__DIR__) . '/vendor/autoload.php';

define('TEMP_PATH', __DIR__ . '/EazyTest/Temp');

$autoload->add('EazyTest', array(__DIR__));
