<?php

namespace spec\SocNet\Communities\Handlers;

use AppBundle\Entity\User;
use SocNet\Communities\Community;
use SocNet\Communities\Commands\JoinCommunityCommand;
use SocNet\Communities\Handlers\JoinCommunityCommandHandler;
use SocNet\Communities\Repositories\CommunitiesRepositoryInterface;
use PhpSpec\ObjectBehavior;

class JoinCommunityCommandHandlerSpec extends ObjectBehavior
{
    function let(CommunitiesRepositoryInterface $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(JoinCommunityCommandHandler::class);
    }

    function it_joins_the_user_to_the_community(
        JoinCommunityCommand $command,
        User $user,
        Community $community,
        CommunitiesRepositoryInterface $repository
    ) {
        $command->getUser()->willReturn($user);
        $command->getCommunity()->willReturn($community);

        $community->addMember($user)->shouldBeCalled();
        $repository->store($community)->shouldBeCalled();

        $this->handle($command);
    }
}
