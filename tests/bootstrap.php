<?php

$loader = require __DIR__  . '/../app/autoload.php';

if (!class_exists('\PHPUnit_Framework_TestCase') && class_exists('\PHPUnit\Framework\TestCase')) {
    class_alias('\PHPUnit\Framework\TestCase', '\PHPUnit_Framework_TestCase');
}

if (!class_exists('\PHPUnit_Util_ErrorHandler') && class_exists('\PHPUnit\Util\ErrorHandler')) {
    class_alias('\PHPUnit\Util\ErrorHandler', '\PHPUnit_Util_ErrorHandler');
}

return $loader;
