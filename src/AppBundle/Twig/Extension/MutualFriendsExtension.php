<?php

namespace AppBundle\Twig\Extension;

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
     * @return \SocNet\Users\User[]
     */
    public function resolveMutualFriends(User $first, User $second)
    {
        return $this->mutualFriendsResolver->forUsers($first, $second);
    }
}
