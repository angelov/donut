<?php

namespace SocNet\Friendships\FriendshipRequests\Commands;

use SocNet\Users\User;

class SendFriendshipRequestCommand
{
    private $sender;
    private $recipient;

    public function __construct(User $sender, User $recipient)
    {
        $this->sender = $sender;
        $this->recipient = $recipient;
    }

    public function getSender() : User
    {
        return $this->sender;
    }

    public function getRecipient() : User
    {
        return $this->recipient;
    }
}
