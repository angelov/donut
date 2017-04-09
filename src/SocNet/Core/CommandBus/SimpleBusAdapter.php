<?php

namespace SocNet\Core\CommandBus;

use SimpleBus\Message\Bus\MessageBus;

class SimpleBusAdapter implements CommandBusInterface
{
    private $messageBus;

    public function __construct(MessageBus $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function handle($command): void
    {
        $this->messageBus->handle($command);
    }
}
