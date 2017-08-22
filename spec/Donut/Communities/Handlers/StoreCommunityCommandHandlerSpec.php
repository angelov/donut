<?php

namespace spec\Angelov\Donut\Communities\Handlers;

use Angelov\Donut\Users\User;
use Prophecy\Argument;
use Angelov\Donut\Communities\Commands\StoreCommunityCommand;
use Angelov\Donut\Communities\Community;
use Angelov\Donut\Communities\Handlers\StoreCommunityCommandHandler;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Communities\Repositories\CommunitiesRepositoryInterface;

class StoreCommunityCommandHandlerSpec extends ObjectBehavior
{
    function let(CommunitiesRepositoryInterface $repository, StoreCommunityCommand $command, User $user)
    {
        $this->beConstructedWith($repository);

        $command->getId()->willReturn('uuid value');
        $command->getName()->willReturn('name');
        $command->getDescription()->willReturn('');
        $command->getAuthor()->willReturn($user);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(StoreCommunityCommandHandler::class);
    }

    function it_stores_new_communities(StoreCommunityCommand $command, CommunitiesRepositoryInterface $repository)
    {
        $repository->store(Argument::type(Community::class))->shouldBeCalled();

        $this->handle($command);
    }
}
