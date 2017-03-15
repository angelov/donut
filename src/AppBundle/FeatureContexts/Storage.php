<?php

namespace AppBundle\FeatureContexts;

// @todo temporary
class Storage
{
    private $items = [];

    public function set(string $key, $value)
    {
        $this->items[$key] = $value;
    }

    public function get(string $key)
    {
        return $this->items[$key];
    }
}
