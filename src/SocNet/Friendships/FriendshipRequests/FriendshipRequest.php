<?php

namespace SocNet\Friendships\FriendshipRequests;

use SocNet\Users\User;
use JMS\Serializer\Annotation as Serializer;

class FriendshipRequest
{
    /**
     * @Serializer\Type(name="string")
     */
    private $id;

    private $fromUser;

    private $toUser;

    public function __construct(string $id, User $sender, User $receiver)
    {
        $this->setFromUser($sender);
        $this->setToUser($receiver);
        $this->setId($id);
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function setId(string $id) : void
    {
        $this->id = $id;
    }

    public function getFromUser() : User
    {
        return $this->fromUser;
    }

    public function setFromUser(User $fromUser) : void
    {
        $this->fromUser = $fromUser;
        $fromUser->addSentFriendshipRequest($this);
    }

    public function getToUser() : User
    {
        return $this->toUser;
    }

    public function setToUser(User $toUser) : void
    {
        $this->toUser = $toUser;
        $toUser->addReceivedFriendshipRequest($this);
    }
}
