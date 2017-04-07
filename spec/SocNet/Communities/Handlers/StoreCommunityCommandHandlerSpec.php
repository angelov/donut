<?php

namespace spec\SocNet\Communities\Handlers;

use AppBundle\Entity\User;
use Prophecy\Argument;
use SocNet\Communities\Commands\StoreCommunityCommand;
use SocNet\Communities\Community;
use SocNet\Communities\Handlers\StoreCommunityCommandHandler;
use PhpSpec\ObjectBehavior;
use SocNet\Communities\Repositories\CommunitiesRepositoryInterface;

class StoreCommunityCommandHandlerSpec extends ObjectBehavior
{
    function let(CommunitiesRepositoryInterface $repository, StoreCommunityCommand $command, User $user)
    {
        $this->beConstructedWith($repository);

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
