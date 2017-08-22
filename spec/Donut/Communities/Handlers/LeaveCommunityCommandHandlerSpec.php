<?php

namespace spec\Angelov\Donut\Communities\Handlers;

use Angelov\Donut\Users\User;
use Prophecy\Argument;
use Angelov\Donut\Communities\Commands\LeaveCommunityCommand;
use Angelov\Donut\Communities\Community;
use Angelov\Donut\Communities\Exceptions\CommunityMemberNotFoundException;
use Angelov\Donut\Communities\Handlers\LeaveCommunityCommandHandler;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Communities\Repositories\CommunitiesRepositoryInterface;

class LeaveCommunityCommandHandlerSpec extends ObjectBehavior
{
    function let(CommunitiesRepositoryInterface $repository, LeaveCommunityCommand $command, User $user, Community $community)
    {
        $this->beConstructedWith($repository);

        $command->getUser()->willReturn($user);
        $command->getCommunity()->willReturn($community);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(LeaveCommunityCommandHandler::class);
    }

    function it_removes_the_member_from_the_community(LeaveCommunityCommand $command, CommunitiesRepositoryInterface $repository, Community $community, User $user)
    {
        $community->removeMember($user)->shouldBeCalled();
        $repository->store($community)->shouldBeCalled();

        $this->handle($command);
    }

    function it_throws_member_not_found_exception_when_trying_to_remove_non_member(LeaveCommunityCommand $command, Community $community)
    {
        $community->removeMember(Argument::type(User::class))->willThrow(CommunityMemberNotFoundException::class);

        $this->shouldThrow(CommunityMemberNotFoundException::class)->during('handle', [$command]);

    }
}
