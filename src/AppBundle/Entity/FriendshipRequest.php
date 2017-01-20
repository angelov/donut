<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="sentFriendshipRequests")
     * @ORM\JoinColumn(name="from_user_id", referencedColumnName="id", nullable=false, onDelete="cascade")
     */
    private $fromUser;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
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
    }

    public function getToUser() : User
    {
        return $this->toUser;
    }

    public function setToUser(User $toUser)
    {
        $this->toUser = $toUser;
    }
}
