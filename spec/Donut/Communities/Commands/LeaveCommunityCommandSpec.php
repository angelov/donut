<?php

namespace spec\Angelov\Donut\Communities\Commands;

use Angelov\Donut\Users\User;
use Angelov\Donut\Communities\Commands\LeaveCommunityCommand;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Communities\Community;

class LeaveCommunityCommandSpec extends ObjectBehavior
{
    function let(User $user, Community $community)
    {
        $this->beConstructedWith($user, $community);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(LeaveCommunityCommand::class);
    }

    function it_holds_the_user(User $user)
    {
        $this->getUser()->shouldReturn($user);
    }

    function it_holds_the_community(Community $community)
    {
        $this->getCommunity()->shouldReturn($community);
    }
}
