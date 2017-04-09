<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SocNet\Users\User;

/**
 * @ORM\Entity
 * @ORM\Table(name="friendship")
 */
class Friendship
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id = '';

    /**
     * @ORM\ManyToOne(targetEntity="SocNet\Users\User", inversedBy="friendships")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */private $user;

    /**
     * @ORM\ManyToOne(targetEntity="SocNet\Users\User")
     * @ORM\JoinColumn(name="friend_id", referencedColumnName="id", nullable=false)
     */
    private $friend;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public static function createBetween(User $user, User $friend)
    {
        $friendship = new self();
        $friendship->setUser($user);
        $friendship->setFriend($friend);

        return $friendship;
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function getUser() : User
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
        $user->addFriendship($this);
    }

    public function getFriend() : User
    {
        return $this->friend;
    }

    public function setFriend(User $friend)
    {
        $this->friend = $friend;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }
}
