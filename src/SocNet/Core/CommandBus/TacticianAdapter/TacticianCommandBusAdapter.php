<?php

namespace SocNet\Core\CommandBus\TacticianAdapter;

use League\Tactician\CommandBus;
use SocNet\Core\CommandBus\CommandBusInterface;

class TacticianCommandBusAdapter implements CommandBusInterface
{
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function handle($command): void
    {
        $this->commandBus->handle($command);
    }
}
