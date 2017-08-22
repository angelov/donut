<?php

namespace Angelov\Donut\Behat\Pages;

interface PageInterface
{
    public function open(array $attributes = []);

    public function isOpen(array $attributes = []) : bool;
}
