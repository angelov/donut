<?php

namespace spec\SocNet\Friendships\FriendshipRequests;

use SocNet\Friendships\FriendshipRequests\FriendshipRequest;
use PhpSpec\ObjectBehavior;
use SocNet\Users\User;

class FriendshipRequestSpec extends ObjectBehavior
{
    public function let(User $sender, User $receiver)
    {
        $this->beConstructedWith($sender, $receiver);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(FriendshipRequest::class);
    }

    function it_has_no_id_by_default()
    {
        $this->getId()->shouldReturn('');
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
