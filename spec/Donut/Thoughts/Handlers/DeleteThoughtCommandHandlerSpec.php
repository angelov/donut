<?php

namespace spec\Angelov\Donut\Thoughts\Handlers;

use Prophecy\Argument;
use Angelov\Donut\Core\EventBus\EventBusInterface;
use Angelov\Donut\Thoughts\Commands\DeleteThoughtCommand;
use Angelov\Donut\Thoughts\Events\ThoughtWasDeletedEvent;
use Angelov\Donut\Thoughts\Handlers\DeleteThoughtCommandHandler;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Thoughts\Repositories\ThoughtsRepositoryInterface;
use Angelov\Donut\Thoughts\Thought;

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
