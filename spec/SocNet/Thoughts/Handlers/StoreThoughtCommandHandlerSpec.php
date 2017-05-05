<?php

namespace spec\SocNet\Thoughts\Handlers;

use Prophecy\Argument;
use SocNet\Core\EventBus\EventBusInterface;
use SocNet\Thoughts\Commands\StoreThoughtCommand;
use SocNet\Thoughts\Events\ThoughtWasPublishedEvent;
use SocNet\Thoughts\Handlers\StoreThoughtCommandHandler;
use PhpSpec\ObjectBehavior;
use SocNet\Thoughts\Repositories\ThoughtsRepositoryInterface;
use SocNet\Thoughts\Thought;

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
