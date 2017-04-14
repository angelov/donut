<?php

namespace SocNet\Core\EventBus;

interface EventBusInterface
{
    public function fire($event) : void;
}
