<?php

namespace spec\SocNet\Thoughts\Handlers;

use Prophecy\Argument;
use SocNet\Core\EventBus\EventBusInterface;
use SocNet\Thoughts\Commands\DeleteThoughtCommand;
use SocNet\Thoughts\Events\ThoughtWasDeletedEvent;
use SocNet\Thoughts\Handlers\DeleteThoughtCommandHandler;
use PhpSpec\ObjectBehavior;
use SocNet\Thoughts\Repositories\ThoughtsRepositoryInterface;
use SocNet\Thoughts\Thought;

class DeleteThoughtCommandHandlerSpec extends ObjectBehavior
{
    function let(ThoughtsRepositoryInterface $repository, EventBusInterface $events)
    {
        $this->beConstructedWith($repository, $events);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DeleteThoughtCommandHandler::class);
    }

    function it_deletes_the_given_thought(DeleteThoughtCommand $command, Thought $thought, ThoughtsRepositoryInterface $repository, EventBusInterface $events)
    {
        $command->getThought()->shouldBeCalled()->willReturn($thought);

        $repository->destroy($thought)->shouldBeCalled();

        $events->fire(Argument::type(ThoughtWasDeletedEvent::class))->shouldBeCalled();

        $this->handle($command);
    }
}
