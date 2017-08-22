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

namespace spec\Angelov\Donut\Users\Security\CurrentUserResolver;

use Angelov\Donut\Users\Security\CurrentUserResolver\CurrentUserResolver;
use Angelov\Donut\Users\User;
use Angelov\Donut\Users\Security\CurrentUserResolver\CurrentUserResolverInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class CurrentUserResolverSpec extends ObjectBehavior
{
    function let(TokenStorageInterface $tokenStorage)
    {
        $this->beConstructedWith($tokenStorage);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CurrentUserResolver::class);
    }

    function it_implements_current_user_resolver_interface()
    {
        $this->shouldImplement(CurrentUserResolverInterface::class);
    }

    function it_throws_exception_when_no_user_is_authenticated(TokenStorageInterface $tokenStorage)
    {
        $tokenStorage->getToken()->willReturn(null);

        $this->shouldThrow(AuthenticationException::class)->during('getUser');
    }

    function it_throws_exception_when_user_is_logged_anonymously(TokenStorageInterface $tokenStorage, TokenInterface $token)
    {
        $tokenStorage->getToken()->willReturn($token);
        $token->getUser()->willReturn('something');

        $this->shouldThrow(AuthenticationException::class)->during('getUser');
    }

    function it_return_current_user(TokenStorageInterface $tokenStorage, TokenInterface $token, User $user)
    {
        $tokenStorage->getToken()->willReturn($token);
        $token->getUser()->willReturn($user);

        $this->getUser()->shouldReturn($user);
    }
}
