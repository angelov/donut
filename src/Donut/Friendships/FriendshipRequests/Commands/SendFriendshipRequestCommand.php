<?php

namespace Angelov\Donut\Friendships\FriendshipRequests\Commands;

use Angelov\Donut\Users\User;

class SendFriendshipRequestCommand
{
    private $id;
    private $sender;
    private $recipient;

    public function __construct(string $id, User $sender, User $recipient)
    {
        $this->sender = $sender;
        $this->recipient = $recipient;
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
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
