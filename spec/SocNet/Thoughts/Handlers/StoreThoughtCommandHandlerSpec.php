<?php

namespace spec\SocNet\Thoughts\Handlers;

use Prophecy\Argument;
use SocNet\Thoughts\Commands\StoreThoughtCommand;
use SocNet\Thoughts\Handlers\StoreThoughtCommandHandler;
use PhpSpec\ObjectBehavior;
use SocNet\Thoughts\Repositories\ThoughtsRepositoryInterface;
use SocNet\Thoughts\Thought;

class StoreThoughtCommandHandlerSpec extends ObjectBehavior
{
    function let(ThoughtsRepositoryInterface $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(StoreThoughtCommandHandler::class);
    }

    function it_stores_the_new_thoughts(StoreThoughtCommand $command, ThoughtsRepositoryInterface $repository)
    {
        $command->getAuthor()->shouldBeCalled();
        $command->getContent()->shouldBeCalled();

        $repository->store(Argument::type(Thought::class))->shouldBeCalled();

        $this->handle($command);
    }
}
