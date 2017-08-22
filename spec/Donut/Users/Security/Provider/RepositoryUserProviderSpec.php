<?php

/**
 * Donut Social Network - Yet another experimental social network.
 * Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
 *
 * This file is part of Donut Social Network.
 *
 * Donut Social Network is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Donut Social Network is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Donut Social Network.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Donut Social Network
 * @copyright Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
 * @license https://github.com/angelov/donut/blob/master/LICENSE
 * @author Dejan Angelov <angelovdejan92@gmail.com>
 */

namespace spec\Angelov\Donut\Users\Security\Provider;

use Angelov\Donut\Core\Exceptions\ResourceNotFoundException;
use Angelov\Donut\Users\Repositories\UsersRepositoryInterface;
use Angelov\Donut\Users\Security\Provider\RepositoryUserProvider;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Users\User;
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
