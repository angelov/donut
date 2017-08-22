<?php

namespace spec\Angelov\Donut\Friendships\FriendshipRequests\Commands;

use Angelov\Donut\Friendships\FriendshipRequests\Commands\SendFriendshipRequestCommand;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Users\User;

class SendFriendshipRequestCommandSpec extends ObjectBehavior
{
    const FRIENDSHIP_REQUEST_ID = 'uuid value';

    function let(User $sender, User $recipient)
    {
        $this->beConstructedWith(self::FRIENDSHIP_REQUEST_ID, $sender, $recipient);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SendFriendshipRequestCommand::class);
    }

    function it_holds_the_id()
    {
        $this->getId()->shouldReturn(self::FRIENDSHIP_REQUEST_ID);
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
