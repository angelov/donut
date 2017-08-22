<?php

namespace Angelov\Donut\Friendships\FriendshipRequests;

use Doctrine\ORM\Mapping as ORM;
use Angelov\Donut\Users\User;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table(name="friendship_request")
 */
class FriendshipRequest
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="guid")
     * @Serializer\Type(name="string")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Angelov\Donut\Users\User", inversedBy="sentFriendshipRequests", cascade={"persist"})
     * @ORM\JoinColumn(name="from_user_id", referencedColumnName="id", nullable=false, onDelete="cascade")
     */
    private $fromUser;

    /**
     * @ORM\ManyToOne(targetEntity="Angelov\Donut\Users\User", inversedBy="receivedFriendshipRequests", cascade={"persist"})
     * @ORM\JoinColumn(name="to_user_id", referencedColumnName="id", nullable=false, onDelete="cascade")
     */
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
