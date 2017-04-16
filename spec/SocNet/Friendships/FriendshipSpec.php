<?php

namespace spec\SocNet\Friendships;

use SocNet\Friendships\Friendship;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SocNet\Users\User;

class FriendshipSpec extends ObjectBehavior
{
    function let(User $user, User $friend)
    {
        $this->beConstructedWith($user, $friend);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Friendship::class);
    }

    function it_has_no_id_by_default()
    {
        $this->getId()->shouldReturn('');
    }

    function it_has_mutable_user(User $user)
    {
        $this->setUser($user);
        $this->getUser()->shouldReturn($user);
    }

    function it_has_mutable_friend(User $user)
    {
        $this->setFriend($user);
        $this->getFriend()->shouldReturn($user);
    }

    function it_has_created_at_date_by_default()
    {
        $this->getCreatedAt()->shouldReturnAnInstanceOf(\DateTime::class);
    }

    function it_has_mutable_created_at_date(\DateTime $createdAt)
    {
        $this->setCreatedAt($createdAt);
        $this->getCreatedAt()->shouldReturn($createdAt);
    }
}
