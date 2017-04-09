<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SocNet\Users\User;

/**
 * @ORM\Entity
 * @ORM\Table(name="friendship_request")
 */
class FriendshipRequest
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="SocNet\Users\User", inversedBy="sentFriendshipRequests")
     * @ORM\JoinColumn(name="from_user_id", referencedColumnName="id", nullable=false, onDelete="cascade")
     */
    private $fromUser;

    /**
     * @ORM\ManyToOne(targetEntity="SocNet\Users\User", inversedBy="receivedFriendshipRequests")
     * @ORM\JoinColumn(name="to_user_id", referencedColumnName="id", nullable=false, onDelete="cascade")
     */
    private $toUser;

    public function getId()
    {
        return $this->id;
    }

    public function getFromUser() : User
    {
        return $this->fromUser;
    }

    public function setFromUser(User $fromUser)
    {
        $this->fromUser = $fromUser;
        $fromUser->addSentFriendshipRequest($this);
    }

    public function getToUser() : User
    {
        return $this->toUser;
    }

    public function setToUser(User $toUser)
    {
        $this->toUser = $toUser;
        $toUser->addReceivedFriendshipRequest($this);
    }
}
