<?php

namespace spec\Angelov\Donut\Core\CommandBus;

use SimpleBus\Message\Bus\MessageBus;
use Angelov\Donut\Core\CommandBus\CommandBusInterface;
use Angelov\Donut\Core\CommandBus\SimpleBusAdapter;
use PhpSpec\ObjectBehavior;

class SimpleBusAdapterSpec extends ObjectBehavior
{
    function let(MessageBus $messageBus)
    {
        $this->beConstructedWith($messageBus);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SimpleBusAdapter::class);
    }

    function it_is_a_command_bus()
    {
        $this->shouldImplement(CommandBusInterface::class);
    }

    function it_passes_commands_to_simplebus($command, MessageBus $messageBus)
    {
        $messageBus->handle($command)->shouldBeCalled();

        $this->handle($command);

    }
}
