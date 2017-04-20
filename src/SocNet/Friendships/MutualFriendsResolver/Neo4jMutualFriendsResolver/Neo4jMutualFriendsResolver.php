<?php

namespace SocNet\Friendships\MutualFriendsResolver\Neo4jMutualFriendsResolver;

use SocNet\Friendships\MutualFriendsResolver\MutualFriendsResolverInterface;
use SocNet\Friendships\MutualFriendsResolver\UsersProvider\UsersProviderInterface;
use SocNet\Users\User;

class Neo4jMutualFriendsResolver implements MutualFriendsResolverInterface
{
    private $usersProvider;
    private $idsResolver;

    public function __construct(IdsResolver $idsResolver, UsersProviderInterface $usersProvider)
    {
        $this->usersProvider = $usersProvider;
        $this->idsResolver = $idsResolver;
    }

    /**
     * @return User[]
     */
    public function forUsers(User $first, User $second): array
    {
        if ($first->equals($second)) {
            return [];
        }

        $ids = $this->idsResolver->findMutualFriends($first->getId(), $second->getId());
        $users = [];

        foreach ($ids as $id) {
            $users[] = $this->usersProvider->getById($id);
        }

        return $users;
    }
}
