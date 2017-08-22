<?php

namespace spec\Angelov\Donut\Thoughts\Handlers;

use Prophecy\Argument;
use Angelov\Donut\Core\EventBus\EventBusInterface;
use Angelov\Donut\Thoughts\Commands\StoreThoughtCommand;
use Angelov\Donut\Thoughts\Events\ThoughtWasPublishedEvent;
use Angelov\Donut\Thoughts\Handlers\StoreThoughtCommandHandler;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Thoughts\Repositories\ThoughtsRepositoryInterface;
use Angelov\Donut\Thoughts\Thought;

class StoreThoughtCommandHandlerSpec extends ObjectBehavior
{
    function let(ThoughtsRepositoryInterface $repository, EventBusInterface $events)
    {
        $this->beConstructedWith($repository, $events);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(StoreThoughtCommandHandler::class);
    }

    function it_stores_the_new_thoughts(StoreThoughtCommand $command, ThoughtsRepositoryInterface $repository, EventBusInterface $events)
    {
        $command->getAuthor()->shouldBeCalled();
        $command->getContent()->shouldBeCalled();
        $command->getId()->shouldBeCalled();
        $command->getCreatedAt()->shouldBeCalled();

        $repository->store(Argument::type(Thought::class))->shouldBeCalled();

        $events->fire(Argument::type(ThoughtWasPublishedEvent::class))->shouldBeCalled();

        $this->handle($command);
    }
}
