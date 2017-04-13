<?php

namespace spec\SocNet\Thoughts\Handlers;

use SocNet\Thoughts\Commands\DeleteThoughtCommand;
use SocNet\Thoughts\Handlers\DeleteThoughtCommandHandler;
use PhpSpec\ObjectBehavior;
use SocNet\Thoughts\Repositories\ThoughtsRepositoryInterface;
use SocNet\Thoughts\Thought;

class DeleteThoughtCommandHandlerSpec extends ObjectBehavior
{
    function let(ThoughtsRepositoryInterface $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DeleteThoughtCommandHandler::class);
    }

    function it_deletes_the_given_thought(DeleteThoughtCommand $command, Thought $thought, ThoughtsRepositoryInterface $repository)
    {
        $command->getThought()->shouldBeCalled()->willReturn($thought);

        $repository->destroy($thought)->shouldBeCalled();

        $this->handle($command);
    }
}
