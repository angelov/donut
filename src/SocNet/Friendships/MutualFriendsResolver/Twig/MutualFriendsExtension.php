<?php

namespace SocNet\Friendships\MutualFriendsResolver\Twig;

use SocNet\Users\User;
use SocNet\Friendships\MutualFriendsResolver\MutualFriendsResolverInterface;

class MutualFriendsExtension extends \Twig_Extension
{
    private $mutualFriendsResolver;

    public function __construct(MutualFriendsResolverInterface $resolver)
    {
        $this->mutualFriendsResolver = $resolver;
    }

    public function getFunctions() : array
    {
        return [
            new \Twig_SimpleFunction('mutual_friends', [$this, 'resolveMutualFriends'])
        ];
    }

    /**
     * @return User[]
     */
    public function resolveMutualFriends(User $first, User $second) : array
    {
        return $this->mutualFriendsResolver->forUsers($first, $second);
    }
}
