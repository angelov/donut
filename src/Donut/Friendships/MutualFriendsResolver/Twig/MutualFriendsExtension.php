<?php

namespace Angelov\Donut\Friendships\MutualFriendsResolver\Twig;

use Angelov\Donut\Users\User;
use Angelov\Donut\Friendships\MutualFriendsResolver\MutualFriendsResolverInterface;

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
