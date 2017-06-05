<?php

namespace SocNet\Users;

use JMS\Serializer\Annotation as Serializer;
use SocNet\Friendships\Friendship;
use SocNet\Friendships\FriendshipRequests\FriendshipRequest;
use SocNet\Places\City;
use SocNet\Thoughts\Thought;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @UniqueEntity(fields={"email"}, message="The email is already in use.")
 * @Serializer\ExclusionPolicy("all")
 */
class User implements UserInterface
{
    /**
     * @Serializer\Type(name="string")
     */
    private $id;

    /**
     * @Serializer\Expose()
     */
    private $email;

    private $password;

    /**
     * @Serializer\Expose()
     */
    private $name;

    private $thoughts;

    /**
     * @Serializer\Expose()
     */
    private $isAdmin = false;

    private $sentFriendshipRequests;

    private $receivedFriendshipRequests;

    private $friendships;

    private $city;

    public function __construct(string $id, string $name, string $email, string $password, City $city)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->thoughts = new ArrayCollection();
        $this->sentFriendshipRequests = new ArrayCollection();
        $this->receivedFriendshipRequests = new ArrayCollection();
        $this->friendships = new ArrayCollection();
        $this->city = $city;
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function setId(string $id) : void
    {
        $this->id = $id;
    }

    public function getUsername() : string
    {
        return $this->email;
    }

    public function setEmail(string $email = '') : void
    {
        $this->email = $email;
    }

    public function getEmail() : string
    {
        return $this->email;
    }

    public function getRoles() : array
    {
        return ['ROLE_USER'];
    }

    public function getPassword() : string
    {
        return $this->password;
    }

    public function setPassword(string $password = '') : void
    {
        $this->password = $password;
    }

    public function getSalt() : string
    {
        return '';
    }

    /**
     * @return Thought[]
     */
    public function getThoughts() : array
    {
        return $this->thoughts->getValues();
    }

    public function addThought(Thought $thought) : void
    {
        $this->thoughts->add($thought);
    }

    public function eraseCredentials() : void
    {
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function setName(string $name = '') : void
    {
        $this->name = $name;
    }

    public function isIsAdmin(): bool
    {
        return $this->isAdmin;
    }

    // @todo remove admin stuff
    public function setIsAdmin(bool $isAdmin) : void
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
    public function getFriends() : array
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
    public function isFriendWith(User $user) : bool
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

    public function getCity() : City
    {
        return $this->city;
    }

    public function setCity(City $city) : void
    {
        $this->city = $city;
    }
}
