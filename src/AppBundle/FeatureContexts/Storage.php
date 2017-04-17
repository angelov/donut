<?php

namespace AppBundle\FeatureContexts;

// @todo temporary
class Storage
{
    private $items = [];

    public function set(string $key, $value) : void
    {
        $this->items[$key] = $value;
    }

    /** @psalm-suppress MissingReturnType */
    public function get(string $key, $default = '')
    {
        return $this->items[$key] ?? $default;
    }
}
