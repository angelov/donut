<?php

namespace spec\SocNet\Friendships;

use SocNet\Friendships\Friendship;
use PhpSpec\ObjectBehavior;
use SocNet\Users\User;

class FriendshipSpec extends ObjectBehavior
{
    const FRIENDSHIP_ID = 'uuid value';

    function let(User $user, User $friend)
    {
        $this->beConstructedWith(self::FRIENDSHIP_ID, $user, $friend);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Friendship::class);
    }

    function it_has_id_by_default()
    {
        $this->getId()->shouldReturn(self::FRIENDSHIP_ID);
    }

    function it_has_mutable_id()
    {
        $this->setId('new');
        $this->getId()->shouldReturn('new');
    }

    function it_has_user_by_default(User $user)
    {
        $this->getUser()->shouldReturn($user);
    }

    function it_has_mutable_user(User $user)
    {
        $this->setUser($user);
        $this->getUser()->shouldReturn($user);
    }

    function it_has_friend_by_default(User $friend)
    {
        $this->getFriend()->shouldReturn($friend);
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
