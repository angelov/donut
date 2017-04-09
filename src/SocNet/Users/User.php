<?php

namespace SocNet\Users;

use AppBundle\Entity\Friendship;
use AppBundle\Entity\FriendshipRequest;
use AppBundle\Entity\Thought;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user")
 * @UniqueEntity(fields={"email"}, message="The email is already in use.")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id = '';

    /**
     * @ORM\Column(type="string", unique=true)
     * @Assert\Email()
     * @Assert\NotBlank(message="Please enter your email.")
     */
    private $email = '';

    /**
     * @ORM\Column(type="string")
     */
    private $password = '';

    /**
     * @Assert\NotBlank(message="Please enter your password.")
     * @Assert\Length(min="6", minMessage="The password must be at least 6 characters long.")
     */
    private $plainPassword = '';

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Please enter your name.")
     */
    private $name = '';

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Thought", mappedBy="author")
     */
    private $thoughts;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $isAdmin = false;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\FriendshipRequest", mappedBy="fromUser")
     */
    private $sentFriendshipRequests;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\FriendshipRequest", mappedBy="toUser")
     */
    private $receivedFriendshipRequests;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Friendship", mappedBy="user", cascade={"remove"})
     */
    private $friendships;

    public function __construct()
    {
        $this->thoughts = new ArrayCollection();
        $this->sentFriendshipRequests = new ArrayCollection();
        $this->receivedFriendshipRequests = new ArrayCollection();
        $this->friendships = new ArrayCollection();
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function setEmail(string $email = '')
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getRoles() : array
    {
        return ['ROLE_USER'];
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword(string $password = '')
    {
        $this->password = $password;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword)
    {
        $this->password = '';
        $this->plainPassword = $plainPassword;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * @return Thought[]
     */
    public function getThoughts() : array
    {
        return $this->thoughts->getValues();
    }

    public function eraseCredentials()
    {
        $this->plainPassword = '';
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name = '')
    {
        $this->name = $name;
    }

    public function isIsAdmin(): bool
    {
        return $this->isAdmin;
    }

    // @todo remove admin stuff
    public function setIsAdmin(bool $isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }

    public function equals(User $user) : bool
    {
        return $this->getId() === $user->getId();
    }

    /**
     * @return FriendshipRequest[]
     */
    public function getSentFriendshipRequests() : array
    {
        return $this->sentFriendshipRequests->getValues();
    }

    public function addSentFriendshipRequest(FriendshipRequest $friendshipRequest) : void
    {
        if (!$this->sentFriendshipRequests->contains($friendshipRequest)) {
            $this->sentFriendshipRequests->add($friendshipRequest);
        }
    }

    public function hasSentFriendshipRequestTo(User $user) : bool
    {
        /** @var FriendshipRequest $friendshipRequest */
        foreach ($this->sentFriendshipRequests as $friendshipRequest) {
            if ($friendshipRequest->getToUser()->equals($user)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return FriendshipRequest[]
     */
    public function getReceivedFriendshipRequests() : array
    {
        return $this->receivedFriendshipRequests->getValues();
    }

    public function addReceivedFriendshipRequest(FriendshipRequest $friendshipRequest) : void
    {
        if (!$this->receivedFriendshipRequests->contains($friendshipRequest)) {
            $this->receivedFriendshipRequests->add($friendshipRequest);
        }
    }

    public function hasReceivedFriendshipRequestFrom(User $user) : bool
    {
        foreach ($this->getReceivedFriendshipRequests() as $friendshipRequest) {
            if ($friendshipRequest->getFromUser()->equals($user)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return User[]
     */
    public function getFriends()
    {
        $friends = [];

        /** @var Friendship $friendship */
        foreach ($this->friendships as $friendship) {
            $friends[] = $friendship->getFriend();
        }

        return $friends;
    }

    public function addFriendship(Friendship $friendship) : void
    {
        $this->friendships->add($friendship);
    }

    // @todo this will have a big effect on performance, use redis or something
    public function isFriendWith(User $user)
    {
        /** @var Friendship $friendship */
        foreach ($this->friendships as $friendship) {
            $friend = $friendship->getFriend();

            if ($friend->equals($user)) {
                return true;
            }
        }

        return false;
    }
}
