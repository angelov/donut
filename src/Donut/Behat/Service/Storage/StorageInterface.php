<?php

namespace Angelov\Donut\Behat\Service\Storage;

interface StorageInterface
{
    public function get(string $key, $default = null);

    public function set(string $key, $value) : void;

    public function all() : array;

    public function clear() : void;
}
