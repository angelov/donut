<?php

namespace spec\SocNet\Core\CommandBus\TacticianAdapter;

use League\Tactician\CommandBus;
use SocNet\Core\CommandBus\CommandBusInterface;
use SocNet\Core\CommandBus\TacticianAdapter\TacticianCommandBusAdapter;
use PhpSpec\ObjectBehavior;

class TacticianCommandBusAdapterSpec extends ObjectBehavior
{
    function let(CommandBus $commandBus)
    {
        $this->beConstructedWith($commandBus);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TacticianCommandBusAdapter::class);
    }

    function it_is_a_command_bus()
    {
        $this->shouldImplement(CommandBusInterface::class);
    }

    function it_passes_commands_to_tactician($command, CommandBus $commandBus)
    {
        $commandBus->handle($command)->shouldBeCalled();

        $this->handle($command);
    }
}
