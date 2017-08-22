<?php

namespace spec\Angelov\Donut\Communities\Commands;

use Angelov\Donut\Users\User;
use Angelov\Donut\Communities\Commands\JoinCommunityCommand;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Communities\Community;

class JoinCommunityCommandSpec extends ObjectBehavior
{
    function let(User $user, Community $community)
    {
        $this->beConstructedWith($user, $community);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(JoinCommunityCommand::class);
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
