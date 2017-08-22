<?php

namespace Angelov\Donut\Core\EventBus;

interface EventBusInterface
{
    public function fire($event) : void;
}
