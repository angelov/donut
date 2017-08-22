<?php

namespace Angelov\Donut\Core\EventBus;

use SimpleBus\Message\Bus\MessageBus;

class SimpleBusAdapter implements EventBusInterface
{
    private $simpleBus;

    public function __construct(MessageBus $messageBus)
    {
        $this->simpleBus = $messageBus;
    }

    public function fire($event): void
    {
        $this->simpleBus->handle($event);
    }
}
