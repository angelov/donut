<?php

namespace SocNet\Friendships;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use SocNet\Users\User;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table(name="friendship")
 */
class Friendship
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="guid")
     * @Serializer\Type(name="string")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="SocNet\Users\User", inversedBy="friendships", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="SocNet\Users\User", cascade={"persist"})
     * @ORM\JoinColumn(name="friend_id", referencedColumnName="id", nullable=false)
     */
    private $friend;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function __construct(string $id, User $user, User $friend)
    {
        $this->createdAt = new DateTime();
        $this->user = $user;
        $this->friend = $friend;
        $this->id = $id;
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function setId(string $id) : void
    {
        $this->id = $id;
    }

    public function getUser() : User
    {
        return $this->user;
    }

    public function setUser(User $user) : void
    {
        $this->user = $user;
        $user->addFriendship($this);
    }

    public function getFriend() : User
    {
        return $this->friend;
    }

    public function setFriend(User $friend) : void
    {
        $this->friend = $friend;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt) : void
    {
        $this->createdAt = $createdAt;
    }
}
