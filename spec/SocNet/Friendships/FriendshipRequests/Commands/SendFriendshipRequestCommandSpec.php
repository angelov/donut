<?php

namespace spec\SocNet\Friendships\FriendshipRequests\Commands;

use SocNet\Friendships\FriendshipRequests\Commands\SendFriendshipRequestCommand;
use PhpSpec\ObjectBehavior;
use SocNet\Users\User;

class SendFriendshipRequestCommandSpec extends ObjectBehavior
{
    function let(User $sender, User $recipient)
    {
        $this->beConstructedWith($sender, $recipient);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SendFriendshipRequestCommand::class);
    }

    function it_holds_the_sender(User $sender)
    {
        $this->getSender()->shouldReturn($sender);
    }

    function it_holds_the_recipient(User $recipient)
    {
        $this->getRecipient()->shouldReturn($recipient);
    }
}
