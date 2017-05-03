<?php

namespace spec\SocNet\Friendships\FriendshipRequests;

use SocNet\Friendships\FriendshipRequests\FriendshipRequest;
use PhpSpec\ObjectBehavior;
use SocNet\Users\User;

class FriendshipRequestSpec extends ObjectBehavior
{
    const REQUEST_ID = 'uuid value';

    function let(User $sender, User $receiver)
    {
        $this->beConstructedWith(self::REQUEST_ID, $sender, $receiver);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(FriendshipRequest::class);
    }

    function it_has_id_by_default()
    {
        $this->getId()->shouldReturn(self::REQUEST_ID);
    }

    function it_has_mutable_id()
    {
        $this->setId('new value');
        $this->getId()->shouldReturn('new value');
    }

    function it_holds_the_sender(User $sender)
    {
        $this->getFromUser()->shouldReturn($sender);
    }

    function it_holds_the_receiver(User $receiver)
    {
        $this->getToUser()->shouldReturn($receiver);
    }

    function it_has_mutable_sender(User $anotherSender)
    {
        $this->setFromUser($anotherSender);
        $this->getFromUser()->shouldReturn($anotherSender);
    }

    function it_has_mutable_receiver(User $anotherReceiver)
    {
        $this->setToUser($anotherReceiver);
        $this->getToUser()->shouldReturn($anotherReceiver);
    }
}
