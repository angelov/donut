<?php

namespace Angelov\Donut\Core\CommandBus;

interface CommandBusInterface
{
    public function handle($command) : void;
}
