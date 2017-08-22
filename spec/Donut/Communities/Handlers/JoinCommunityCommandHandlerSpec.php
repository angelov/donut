<?php

namespace spec\Angelov\Donut\Communities\Handlers;

use Angelov\Donut\Users\User;
use Angelov\Donut\Communities\Community;
use Angelov\Donut\Communities\Commands\JoinCommunityCommand;
use Angelov\Donut\Communities\Handlers\JoinCommunityCommandHandler;
use Angelov\Donut\Communities\Repositories\CommunitiesRepositoryInterface;
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
