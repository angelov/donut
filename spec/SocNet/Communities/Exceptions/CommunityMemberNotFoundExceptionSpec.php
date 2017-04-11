<?php

namespace spec\SocNet\Communities\Exceptions;

use SocNet\Users\User;
use SocNet\Communities\Community;
use SocNet\Communities\Exceptions\CommunityMemberNotFoundException;
use PhpSpec\ObjectBehavior;

class CommunityMemberNotFoundExceptionSpec extends ObjectBehavior
{
    function let(User $user, Community $community)
    {
        $this->beConstructedWith($user, $community);

        $user->getName()->willReturn('John Smith');
        $community->getName()->willReturn('PHP Developers');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CommunityMemberNotFoundException::class);
    }

    function it_is_runtime_exception()
    {
        $this->shouldBeAnInstanceOf(\RuntimeException::class);
    }

    function it_contains_the_user(User $user)
    {
        $this->getMember()->shouldReturn($user);
    }

    function it_contains_the_community(Community $community)
    {
        $this->getCommunity()->shouldReturn($community);
    }

    function it_has_message_by_default(User $user, Community $community)
    {
        $this->getMessage()->shouldReturn(
            'The given user ["John Smith"] is not part of the community ["PHP Developers"]'
        );
    }
}
