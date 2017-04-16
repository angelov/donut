<?php

namespace spec\SocNet\Users\Security\Provider;

use SocNet\Core\Exceptions\ResourceNotFoundException;
use SocNet\Users\Repositories\UsersRepositoryInterface;
use SocNet\Users\Security\Provider\RepositoryUserProvider;
use PhpSpec\ObjectBehavior;
use SocNet\Users\User;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class RepositoryUserProviderSpec extends ObjectBehavior
{
    const USER_EMAIL = 'john@example.com';

    function let(UsersRepositoryInterface $users)
    {
        $this->beConstructedWith($users);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RepositoryUserProvider::class);
    }

    function it_is_symfony_user_provider()
    {
        $this->shouldImplement(UserProviderInterface::class);
    }

    function it_supports_user_class()
    {
        $this->supportsClass(User::class)->shouldReturn(true);
    }

    function it_does_not_support_other_classes()
    {
        $this->supportsClass('Some/Random/Class')->shouldReturn(false);
    }

    function it_loads_users_by_email(UsersRepositoryInterface $users, User $user)
    {
        $users->findByEmail(self::USER_EMAIL)->willReturn($user);

        $this->loadUserByUsername(self::USER_EMAIL)->shouldReturn($user);
    }

    function it_throws_exception_if_user_is_not_found(UsersRepositoryInterface $users)
    {
        $users->findByEmail(self::USER_EMAIL)->willThrow(ResourceNotFoundException::class);

        $exception = new UsernameNotFoundException('Could not find a user with email [' . self::USER_EMAIL . ']');

        $this->shouldThrow($exception)->during('loadUserByUsername', [self::USER_EMAIL]);
    }

    function it_refreshes_users(User $original, UsersRepositoryInterface $users, User $refreshed)
    {
        $original->getUsername()->willReturn(self::USER_EMAIL);
        $users->findByEmail(self::USER_EMAIL)->willReturn($refreshed);

        $this->refreshUser($original)->shouldReturn($refreshed);
    }
}
