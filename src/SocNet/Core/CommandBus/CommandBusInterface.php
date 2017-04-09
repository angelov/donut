<?php

namespace SocNet\Core\CommandBus;

interface CommandBusInterface
{
    public function handle($command) : void;
}
